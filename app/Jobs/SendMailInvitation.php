<?php

namespace App\Jobs;

use App\Mail\InvitationMail;
use App\Models\Company;
use App\Models\Invitation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $invitation_uuid;

    /**
     * Create a new job instance.
     *
     * @param $invitation_uuid
     */
    public function __construct($invitation_uuid)
    {
        $this->invitation_uuid = $invitation_uuid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invitation = Invitation::with(['template', 'company'])
            ->where('uuid', $this->invitation_uuid)
            ->first();

        $exist = Invitation::where([
            ['email', $invitation->email],
            ['reference_number', $invitation->reference_number],
            ['company_id', $invitation->company_id],
            ['id', '!=', $invitation->id],
            ['status', Invitation::STATUS_DELIVERED],
        ])->count();

        if ($exist) {
            $invitation->status = Invitation::STATUS_NOT_DELIVERED;
            $invitation->save();
            return;
        }

        $params = [
            'name' => $invitation->name,
            'reference_number' => $invitation->reference_number,
            'sender_name' => $invitation->sender_name,
            'sender_email' => $invitation->sender_email,
            'reply_to_email' => $invitation->reply_to_email,
            'subject' => $invitation->subject,
        ];

        $template = $invitation->template;
        if ($template) {
            $content = $template->content;
            $company = $invitation->company;
            $replaceText = [
                'Name' => $invitation->name,
                'CompanyIdentifier' => $company->name,
                'Link' => '',
                'Stars' => view('mails.includes.star', ['link' => ''])->render(),
                'LegalNotice' => '',
            ];
            $params['body'] = preg_replace_callback('/\[(.*?)]/i', function ($content) use ($replaceText) {
                if (isset($content[1]) && isset($replaceText[$content[1]])) return $replaceText[$content[1]];
                return '';
            }, $content);

            Mail::to($invitation->email)->send(new InvitationMail($params));

            $invitation->status = Invitation::STATUS_DELIVERED;
            $invitation->sent_at = Carbon::now();
            $invitation->save();
        }
        return;
    }
}
