<?php

namespace Modules\GlPostingSettings\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\GlPostingSettings\Emails\SendPaymentReceivedEmail;
use Modules\GlPostingSettings\Models\GlPostingReceipt;

class GlPostingReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $receipt;
    public $send_receipt;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GlPostingReceipt $receipt, $send_receipt=0){
        $this->receipt = $receipt;
        $this->send_receipt = intval($send_receipt) == 1;
    }
    
    
    public function handle()
    {
        $receipt = $this->receipt;

        if($receipt->ammendment > 1){
            $receipt->number = $receipt->number.'-v'.$receipt->ammendment;
        }

        $view = view('gl-posting-settings::receipt', compact('receipt'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $receipt->number.'.pdf';

        $pdf_path = storage_path('app/temp/' . $file_name);
		$receipt->download_link = $pdf_path;
        $receipt->save();

        // Save the PDF file into temp folder
        $pdf->save($pdf_path);

        if($this->send_receipt){
            $contact = $this->receipt->customer;
            $creator = $this->receipt->creator;
            Mail::to($contact)->cc($creator)->send(new SendPaymentReceivedEmail($receipt, $contact));
        }
    }
}
