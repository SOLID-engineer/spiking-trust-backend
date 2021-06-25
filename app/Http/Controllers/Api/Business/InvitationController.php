<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\MailTemplate;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class InvitationController extends Controller
{
    public function emailInvitationsBulk(Request $request)
    {
        $company = $request->get('company');
        $invitations = $request->post('invitations');
        $replyTo = $request->post('replyTo');
        $senderName = $request->post('senderName');
        $templateId = $request->post('templateId');
        if (empty($invitations)) return response()->json('', 400);
        $template = MailTemplate::where('uuid', $templateId)->first();
        if (empty($template)) return response()->json('', 400);
        \DB::beginTransaction();
        try {
            foreach ($invitations as $invitation) {
                Invitation::create([
                    'company_id' => $company->id,
                    'name' => $invitation['consumerName'],
                    'email' => $invitation['consumerEmail'],
                    'reference_number' => $invitation['referenceNumber'],
                    'type' => Invitation::TYPE_SERVICE_REVIEW,
                    'sender_name' => $senderName,
                    'sender_email' => 'hello@abc.com',
                    'reply_to_email' => $replyTo,
                    'template_id' => $template->id,
                ]);
            }
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json('', 500);
        }
        return response()->json('');
    }
}
