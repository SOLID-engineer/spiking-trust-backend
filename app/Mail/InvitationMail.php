<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;
    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Setting::setMailConfigBeforeSend();
        $subject = isset($this->data['subject']) ? $this->data['subject'] : "";
        $sender_mail = isset($this->data['sender_email']) ? $this->data['sender_email'] : "";
        $sender_name = isset($this->data['sender_name']) ? $this->data['sender_name'] : "";

        return $this->view('mails.invite-mail')->with($this->data)
            ->from($sender_mail, $sender_name)->subject($subject);
    }
}
