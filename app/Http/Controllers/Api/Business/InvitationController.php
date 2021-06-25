<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailInvitation;
use App\Models\Invitation;
use App\Models\MailTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Ramsey\Uuid\Uuid;

class InvitationController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
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
        $params = [];
        $paramsU = [];
        try {
            foreach ($invitations as $invitation) {
                $uuid = Uuid::uuid4();
                SendMailInvitation::dispatch($uuid);
                $paramsU[] = $uuid;
                $params[] = [
                    'uuid' => $uuid,
                    'company_id' => $company->id,
                    'name' => $invitation['consumerName'],
                    'email' => $invitation['consumerEmail'],
                    'reference_number' => $invitation['referenceNumber'],
                    'type' => Invitation::TYPE_SERVICE_REVIEW,
                    'sender_name' => $senderName,
                    'sender_email' => 'hello@abc.com',
                    'reply_to_email' => $replyTo,
                    'template_id' => $template->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            Invitation::insert($params);
            \DB::commit();
            return response()->json('');
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e, 500);
        }
    }
}
