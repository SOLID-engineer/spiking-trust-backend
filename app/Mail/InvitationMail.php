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
        $mail_setting = Setting::where('type', Setting::MAIL_SETTINGS)->get();
        if ($mail_setting->isNotEmpty()) {
            $mail_setting = $mail_setting->keyBy('key');
        }

        if (!empty($mail_setting['MAIL_HOST']['value'])) {
            Config::set('mail.mailers.smtp.host', $mail_setting['MAIL_HOST']['value']);
        }
        if (!empty($mail_setting['MAIL_PORT']['value'])) {
            Config::set('mail.mailers.smtp.port', $mail_setting['MAIL_PORT']['value']);
        }
        if (!empty($mail_setting['MAIL_ENCRYPTION']['value'])) {
            Config::set('mail.mailers.smtp.encryption', $mail_setting['MAIL_ENCRYPTION']['value']);
        }
        if (!empty($mail_setting['MAIL_USERNAME']['value'])) {
            Config::set('mail.mailers.smtp.username', $mail_setting['MAIL_USERNAME']['value']);
        }
        if (!empty($mail_setting['MAIL_PASSWORD']['value'])) {
            Config::set('mail.mailers.smtp.password', $mail_setting['MAIL_PASSWORD']['value']);
        }
        $subject = isset($this->data['subject']) ? $this->data['subject'] : "";
        $sender_mail = isset($this->data['sender_email']) ? $this->data['sender_email'] : "";
        $sender_name = isset($this->data['sender_name']) ? $this->data['sender_name'] : "";

        return $this->view('mails.invite-mail')->with($this->data)
            ->from($sender_mail, $sender_name)->subject($subject);
    }
}
