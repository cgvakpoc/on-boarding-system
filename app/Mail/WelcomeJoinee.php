<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Libraries\Common;

class WelcomeJoinee extends Mailable
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
        $template = $this->view('mail.welcome_joinee')->with(['content' => $this->template_details['template_details']]);
        $template->from($this->template_details['from']);
        $template->subject($this->template_details['subject']);
        $template = Common::generateMailTo($template,$this->template_details);
        $template = Common::generateMailCc($template,$this->template_details);
        $template = Common::generateMailBcc($template,$this->template_details);
        $template = Common::generateMailAttachments($template,$this->template_details);
        return $template;
    }
}
