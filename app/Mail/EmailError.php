<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Libraries\Common;

class EmailError extends Mailable
{
    use Queueable, SerializesModels;
    public $template_details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($template_details)
    {
          $this->template_details = $template_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = $this->view('mail.error');
        $template->from(config('constants.ERROR_NOTIFICATION_FROM_EMAIL'));
        $template->to(config('constants.ERROR_NOTIFICATION_TO_EMAIL'));
        $template->subject('Mail Sending Failed - On Boarding System');
        return $template;
    }
}
