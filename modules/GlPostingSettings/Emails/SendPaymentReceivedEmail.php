<?php

namespace Modules\GlPostingSettings\Emails;

use App\Models\Common\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GlPostingSettings\Models\GlPostingReceipt;

class SendPaymentReceivedEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $receipt;
    public $contact;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(GlPostingReceipt $receipt, Contact $contact)
    {
        $this->receipt = $receipt;
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('gl-posting-settings::emails.email-receipt')->attach($this->receipt->download_link);
    }
}
