<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Demarcations\Models\Demarcation;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BackfillDemarcationBankDetails extends Command
{
    protected $signature = 'demarcations:backfill-bank-details
        {path? : Path to the bank payments Excel template}
        {--company=1 : Company id}
        {--dry-run : Show matches without writing}';

    protected $description = 'Backfill demarcation account numbers, bank codes, and branch codes from a bank payments Excel template';

    protected array $bankNames = [
        '01' => 'KCB Bank',
        '03' => 'Absa Bank',
        '07' => 'NCBA Bank',
        '11' => 'Co-operative Bank',
        '12' => 'National Bank',
        '14' => 'M-Oriental Bank',
        '19' => 'Bank of Africa',
        '26' => 'Access Bank',
        '51' => 'Kingdom Bank',
        '62' => 'Postbank',
        '68' => 'Equity Bank',
        '70' => 'Family Bank',
        '79' => 'Faulu Microfinance Bank',
    ];

    public function handle(): int
    {
        $companyId = (int) $this->option('company');
        company($companyId);

        $path = $this->argument('path')
            ?: '/Users/kimari/Downloads/BANK PAYMENTS TEMPLATE OCTOBER UPDATE.xlsx';

        if (!is_file($path)) {
            $this->error('Excel file not found: ' . $path);

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $rows = $this->readExcel($path);
        $this->ensureBanks($rows, $dryRun);

        $dems = Demarcation::query()
            ->whereIn('type', ['Region', 'Sub Region'])
            ->get(['id', 'name', 'code', 'type', 'bank_id', 'account_number', 'branch_code']);

        $banksByCode = DB::table('banks')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get()
            ->unique(function ($bank) {
                return str_pad((string) $bank->code, 2, '0', STR_PAD_LEFT);
            })
            ->keyBy(function ($bank) {
                return str_pad((string) $bank->code, 2, '0', STR_PAD_LEFT);
            });

        $matched = 0;
        $updated = 0;
        $unmatched = [];

        foreach ($rows as $row) {
            $matches = $this->matchDemarcations($row, $dems);
            if ($matches->isEmpty()) {
                $unmatched[] = $row['name'];
                continue;
            }

            $matched += $matches->count();
            $bankCode = str_pad((string) $row['bank_code'], 2, '0', STR_PAD_LEFT);
            $bankId = optional($banksByCode->get($bankCode))->id;

            foreach ($matches as $dem) {
                $payload = [
                    'account_number' => $row['account'],
                    'branch_code' => $row['branch_code'] !== '' ? $row['branch_code'] : null,
                    'bank_id' => $bankId,
                ];

                $changed = (string) $dem->account_number !== (string) $payload['account_number']
                    || (string) $dem->branch_code !== (string) ($payload['branch_code'] ?? '')
                    || (int) ($dem->bank_id ?? 0) !== (int) ($bankId ?? 0);

                if (!$changed) {
                    continue;
                }

                $updated++;
                if (!$dryRun) {
                    DB::table('demarcations')->where('id', $dem->id)->update(array_merge($payload, [
                        'updated_at' => now(),
                    ]));
                    $dem->account_number = $payload['account_number'];
                    $dem->branch_code = $payload['branch_code'];
                    $dem->bank_id = $bankId;
                }
            }
        }

        $this->info(($dryRun ? '[dry-run] ' : '') . "Excel rows: " . count($rows));
        $this->info("Matched demarcation updates considered: {$matched}");
        $this->info("Rows to update: {$updated}");
        $this->info('Unmatched excel names: ' . count($unmatched));

        foreach (array_slice($unmatched, 0, 40) as $name) {
            $this->line('  - ' . $name);
        }

        if (count($unmatched) > 40) {
            $this->line('  ... and ' . (count($unmatched) - 40) . ' more');
        }

        return self::SUCCESS;
    }

    protected function readExcel(string $path): array
    {
        $sheet = IOFactory::load($path)->getActiveSheet();
        $rows = [];

        foreach ($sheet->toArray() as $i => $row) {
            if ($i === 0) {
                continue;
            }

            $name = trim((string) ($row[1] ?? ''));
            $account = trim((string) ($row[2] ?? ''));
            $bankCode = trim((string) ($row[3] ?? ''));
            $branchCode = trim((string) ($row[4] ?? ''));

            if ($name === '' || $account === '') {
                continue;
            }

            if (is_numeric($bankCode)) {
                $bankCode = str_pad($bankCode, 2, '0', STR_PAD_LEFT);
            }

            $rows[] = [
                'name' => $name,
                'account' => $account,
                'bank_code' => $bankCode,
                'branch_code' => $branchCode,
                'norm' => $this->normalize($name),
                'type' => $this->guessType($name),
            ];
        }

        return $rows;
    }

    protected function ensureBanks(array $rows, bool $dryRun): void
    {
        $existing = DB::table('banks')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get()
            ->unique(function ($bank) {
                return str_pad((string) $bank->code, 2, '0', STR_PAD_LEFT);
            })
            ->keyBy(function ($bank) {
                return str_pad((string) $bank->code, 2, '0', STR_PAD_LEFT);
            });

        foreach (collect($rows)->pluck('bank_code')->unique()->filter() as $code) {
            $code = str_pad((string) $code, 2, '0', STR_PAD_LEFT);
            if ($existing->has($code)) {
                continue;
            }

            $name = $this->bankNames[$code] ?? ('Bank ' . $code);
            $this->line(($dryRun ? '[dry-run] ' : '') . "Create bank {$code} {$name}");

            if ($dryRun) {
                continue;
            }

            $id = DB::table('banks')->insertGetId([
                'company_id' => company_id() ?: 1,
                'name' => $name,
                'code' => $code,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $existing[$code] = (object) ['id' => $id, 'code' => $code, 'name' => $name];
        }
    }

    protected function matchDemarcations(array $row, $dems)
    {
        $norm = $row['norm'];
        $type = $row['type'];

        $byExcel = $this->manualByExcelName()[trim($row['name'])] ?? null;
        if ($byExcel === false) {
            return collect();
        }
        if (is_int($byExcel)) {
            return $dems->where('id', $byExcel)->values();
        }

        $manual = $this->manualMap()[$norm] ?? null;
        if ($manual === false) {
            return collect();
        }

        if (is_int($manual)) {
            return $dems->where('id', $manual)->values();
        }

        if (is_string($manual)) {
            $target = $this->normalize($manual);
            $hits = $dems->filter(function ($d) use ($target, $type) {
                if ($this->normalize($d->name) !== $target) {
                    return false;
                }
                return !$type || $d->type === $type;
            });

            if ($hits->isNotEmpty()) {
                return $hits->values();
            }
        }

        $exact = $dems->filter(function ($d) use ($norm, $type) {
            if ($this->normalize($d->name) !== $norm) {
                return false;
            }
            return !$type || $d->type === $type;
        });

        if ($exact->isNotEmpty()) {
            return $exact->values();
        }

        $best = collect();
        $bestScore = 0;
        foreach ($dems as $d) {
            if ($type && $d->type !== $type) {
                continue;
            }
            $score = $this->score($norm, $this->normalize($d->name));
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = collect([$d]);
            } elseif ($score === $bestScore && $score > 0) {
                $best->push($d);
            }
        }

        if ($bestScore >= 0.9) {
            // Same display name duplicates (e.g. MASABA x2): update all
            $names = $best->pluck('name')->map(fn ($n) => mb_strtoupper((string) $n))->unique();
            if ($names->count() === 1 || $best->count() === 1) {
                return $best->values();
            }
        }

        return collect();
    }

    protected function manualByExcelName(): array
    {
        return [
            'AMANI SUB REGION NJORO SOUTH' => 138,
            'DELIVERANCE CHURCH-AMANI SUB REGION' => 481,
            'D.C KISII SUB REGION' => 394,
            'DELIVERANCE CHURCH KISII SOUTH' => 382,
            'D.C SOUTH NYANZA REGION' => false,
            'DELIVERANCE CHURCH SOUTH NYANZA W R' => 323,
            'DELIVERANCE CHURCH SIGOR' => false,
            'DELIVERANCE CHURCH SIGOR SUB REGION' => 120,
            'DELIVERANCE CHURCH LUGARI STN SUB R' => 164,
            'DELIVERANCE CHURCH MAPARASHA S-REG' => 75,
            'DELIVERANCE CHURCH MOI UNIV S REG' => 194,
            'DELIVERANCE CHURCH RUARAKA SUB R CC' => 400,
            'HILLSVIEW SUB REGION' => 403, // HILL VIEW
            'DELIVERANCE CHURCH UTAWALA REGION' => 346,
            'DELIVERANCE CHURCH MAKUTANO' => 132,
            'DELIVERANCE CHURCH MAKUTANO S REG' => 55,
            'DELIVERANCE CHURCH MUKUYUNI SUB RE' => 166,
            'DELIVERANCE CHURCH MUKUYUNI SUB-RE' => 487,
            'DELIVERANCE CHURCH DAGORETTI N S R' => 365,
            'DELIVERANCE CHURCH DAGORETTI SUB R' => 370,
            'DELIVERANCE CHURCH INT MALINDI WSR' => 276,
            'DELIVERANCE CHURCH INT NDEGE SUB R' => 140,
            'DELIVERANCE CHURCH INT THARAKA' => false,
            'DELIVERANCE CHURCH INTL RUAKA S-REG' => 453,
            'DELIVERANCE CHURCH KASARANI S RCC' => 360,
            'DELIVERANCE CHURCH CENTRAL SUB REG' => false,
            'DELIVERANCE CHURCH NYAMBENE W S RE' => 92,
            'Deliverance church INL Nandi C SR' => 521,
            'DELIVERANCE CHURCH INTERNATIONAL AM' => false,
            "DELIVERANCE CHURCH INT'L -MSR" => false,
            'DELIVERANCE KITISURU SUBREGION' => false,
            'CHEBUNYO DELIVERANCE CHURCH' => false,
            'DELIVERANCE CHURCH AGET SUB REGION' => false,
            'DELIVERANCE CHURCH FELLOWSHIP' => false,
            'DELIVERANCE CHURCH GITHIGA SUB REGION' => false,
            'DELIVERANCE CHURCH MPEKETONI' => false,
            'DELIVERANCE CHURCH NKARONI SUB REGION' => false,
            'DELIVERANCE CHURCH OTHAYA SUB-RE' => false,
            'DELIVERANCE CHURCH SIMAMWET SUB RE' => false,
            'KAPSASIAN DC' => false,
            'OLMEKENYU SUB REGION' => false,
            'DELIVERANCE CHURCH' => false,
        ];
    }

    protected function manualMap(): array
    {
        return [
            'AMANI NJORO SOUTH' => 'AMANI',
            'AMBOSELI EASTL' => 'AMBOSELI EAST',
            'INTERNATIONAL MENENGAI WEST' => 'MENENGAI WEST',
            'KENYATTA ROAD' => 'KENYATTA',
            'NK WEST' => 'NAKURU WEST',
            'BROOKE' => 'BROOKE EAST',
            'KIENI WEST' => 'NEW KIENI WEST',
            'DAGORETTI NORTH' => 'DAGORETI NORTH',
            'DAGORETTI SOUTH' => 'DAGORETI SOUTH',
            'DAGORETTI N' => 'DAGORETI NORTH',
            'IMANI TIRAP' => 'TIRAP',
            'INTERNATIONAL MALINDI WEST' => 'MALINDI WEST',
            'INTERNATIONAL NDEGE' => 'NDEGE',
            'INTERNATIONAL RUAKA' => 'RUAKA',
            'INTERNATIONAL RUAKA SOUTH' => 'RUAKA',
            'INTERNATIONAL NANDI CENTRAL' => 'NANDI CENTRAL',
            'KASARANI SOUTH' => 'KASARANI',
            'LUKENYA' => 'LUKENYA-MACHAKOS',
            'MKS CENTRAL' => 'MACHAKOS CENTRAL',
            'MOI UNIVERSITY SOUTH' => 'MOI UNIVERSITY',
            'MAKUTANO SOUTH' => 'MAKUTANO - MWEA',
            'MAPARASHA SOUTH' => 'MAPARASHA',
            'HOMA BAY WEST' => 'HOMABAY WEST',
            'OL JORO OROK' => 'OLJORO OROK',
            'TRANS MARA' => 'TRANSMARA',
            'TIMBOROA SUBU' => 'TIMBOROA/SUBUKIA',
            'TIMBOROA SUBUKIA' => 'TIMBOROA/SUBUKIA',
            'SAIKA CENTRAL CENTRAL' => 'SAIKA',
            'KITISURU' => false,
            'CHEBUNYO' => false,
            'AGET' => false,
            'FELLOWSHIP' => false,
            'GITHIGA' => false,
            'MPEKETONI' => false,
            'NKARONI' => false,
            'OTHAYA' => false,
            'SIMAMWET' => false,
            'KAPSASIAN' => false,
            'OLMEKENYU' => false,
            '' => false,
        ];
    }

    protected function normalize(string $name): string
    {
        $s = mb_strtoupper(trim($name));
        $s = str_replace(["'", '’'], '', $s);
        $s = preg_replace('/[^A-Z0-9 \/\-]+/', ' ', $s) ?? $s;
        $s = preg_replace('/\bDELIVERANCE\b/', ' ', $s) ?? $s;
        $s = preg_replace('/\bCHURCH\b/', ' ', $s) ?? $s;
        $s = preg_replace('/\bD\.?\s*C\.?\b/', ' ', $s) ?? $s;
        $s = preg_replace('/\bACCOUNT\b/', ' ', $s) ?? $s;
        $s = preg_replace('/\bFUNDS\b/', ' ', $s) ?? $s;
        $s = preg_replace('/\bSUB[-\s\.]*REG(?:ION(?:AL)?)?\b/u', ' ', $s) ?? $s;
        $s = preg_replace('/\bSUB[-\s]*RE\b/u', ' ', $s) ?? $s;
        $s = preg_replace('/\bSUB[-\s]*R\b/u', ' ', $s) ?? $s;
        $s = preg_replace('/\bS[-\s]*REG\b/u', ' ', $s) ?? $s;
        $s = preg_replace('/\bS[-\s]*RE\b/u', ' ', $s) ?? $s;
        $s = preg_replace('/\bS[-\s]*R\b/u', ' ', $s) ?? $s;
        $s = preg_replace('/\bWSR\b/u', ' WEST ', $s) ?? $s;
        $s = preg_replace('/\bREGION\b/u', ' ', $s) ?? $s;
        $s = preg_replace('/\bREG\b/u', ' ', $s) ?? $s;
        $s = preg_replace('/\bSUB\b/u', ' ', $s) ?? $s;
        $s = str_replace(['-', '/', '_', '.'], ' ', $s);
        $s = preg_replace('/\s+/', ' ', $s) ?? $s;
        $s = $this->expandAbbrev(trim($s));
        $s = preg_replace('/\b(RE|R)\b$/', '', $s) ?? $s;

        return trim(preg_replace('/\s+/', ' ', $s) ?? $s);
    }

    protected function expandAbbrev(string $s): string
    {
        $map = [
            'NTH' => 'NORTH',
            'STH' => 'SOUTH',
            'EST' => 'EAST',
            'WST' => 'WEST',
            'CENT' => 'CENTRAL',
            'INT' => 'INTERNATIONAL',
            'INTL' => 'INTERNATIONAL',
            'INL' => 'INTERNATIONAL',
            'SHP' => 'TOWNSHIP',
            'STN' => 'STATION',
            'UNIV' => 'UNIVERSITY',
            'RD' => 'ROAD',
        ];

        $parts = preg_split('/\s+/', $s) ?: [];
        $out = [];
        foreach ($parts as $p) {
            if (in_array($p, ['N', 'S', 'E', 'W', 'C'], true)) {
                $out[] = ['N' => 'NORTH', 'S' => 'SOUTH', 'E' => 'EAST', 'W' => 'WEST', 'C' => 'CENTRAL'][$p];
                continue;
            }
            if (isset($map[$p])) {
                $out[] = $map[$p];
                continue;
            }
            $out[] = $p;
        }

        return trim(implode(' ', $out));
    }

    protected function guessType(string $excelName): ?string
    {
        $u = mb_strtoupper($excelName);
        if (preg_match('/\bSUB[-\s\.]*REG|\bS[-\s]*R\b|\bSUBREGION\b|\bSUB\s*RE\b/', $u)) {
            return 'Sub Region';
        }
        if (preg_match('/\bREGION\b|\bREG\b/', $u)) {
            return 'Region';
        }

        return null;
    }

    protected function score(string $a, string $b): float
    {
        if ($a === $b) {
            return 1.0;
        }
        if ($a === '' || $b === '') {
            return 0.0;
        }
        similar_text($a, $b, $pct);
        $ta = array_values(array_filter(explode(' ', $a)));
        $tb = array_values(array_filter(explode(' ', $b)));
        $inter = count(array_intersect($ta, $tb));
        $union = max(count(array_unique(array_merge($ta, $tb))), 1);

        return max($pct / 100, ($inter / $union));
    }
}
