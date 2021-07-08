<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClaimMail extends Mailable
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
        $setting = Setting::where('type', Setting::MAIL_INVITATION)->first();
        $content = $setting->value;
        $href = env('FRONTEND_URL').'/claim-company/active?v='.isset($token) ?? '';
        $replaceText = [
            'Name' => $this->data['name'],
            'Domain' => $this->data['domain'],
            'Token' => $this->data['token'],
            'Href' => $href,
        ];
        $body = preg_replace_callback('/\[(.*?)]/i', function ($content) use ($replaceText) {
            if (isset($content[1]) && isset($replaceText[$content[1]])) return $replaceText[$content[1]];
            return '';
        }, $content);
        return $this->html($body);
    }
}
