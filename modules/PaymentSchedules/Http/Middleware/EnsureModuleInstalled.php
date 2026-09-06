<?php

namespace Modules\PaymentSchedules\Http\Middleware;

use Closure;
use Modules\PaymentSchedules\Utilities\InstallModule;

class EnsureModuleInstalled
{
    public function handle($request, Closure $next)
    {
        if (company_id()) {
            try {
                (new InstallModule())->ensure();
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $next($request);
    }
}
