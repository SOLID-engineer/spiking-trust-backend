<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
        $subject = isset($this->data['subject']) ? $this->data['subject'] : "";
        return $this->view('mails.claim-mail')->with([
            'name' => isset($this->data['name']) ? $this->data['name'] : "",
            'domain' => isset($this->data['domain']) ? $this->data['domain'] : "",
            'token' => isset($this->data['token']) ? $this->data['token'] : "",
            'body' => isset($this->data['body']) ? $this->data['body'] : "",
        ])->subject($subject);
    }
}
