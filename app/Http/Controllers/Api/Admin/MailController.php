<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MailController extends Controller
{
    /**
     * @param Request $request
     * @param $domain
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $settings = Setting::where('type', Setting::MAIL_SETTINGS)->get();
        $mail_setting = [];
        foreach ($settings as $key => $setting) {
            $mail_setting[$setting->key] = $setting->value;
        }

        return response()->json($mail_setting, 200);
    }

    /**
     * @param Request $request
     * @param $domain
     * @return JsonResponse
     */
    public function setting(Request $request)
    {

        $mail_server = $request->get('mail_server', '');
        $port = $request->get('port', '');
        $username = $request->get('username', '');
        $password = $request->get('password', '');
        $encryption = $request->get('encryption', '');

        $inserts[] = [
            "key" => 'MAIL_HOST',
            "value" => $mail_server,
            "type" => Setting::MAIL_SETTINGS,
        ];
        $inserts[] = [
            "key" => 'MAIL_PORT',
            "value" => $port,
            "type" => Setting::MAIL_SETTINGS,
        ];
        $inserts[] = [
            "key" => 'MAIL_USERNAME',
            "value" => $username,
            "type" => Setting::MAIL_SETTINGS,
        ];
        $inserts[] = [
            "key" => 'MAIL_PASSWORD',
            "value" => $password,
            "type" => Setting::MAIL_SETTINGS,
        ];
        $inserts[] = [
            "key" => 'MAIL_ENCRYPTION',
            "value" => $encryption,
            "type" => Setting::MAIL_SETTINGS,
        ];

        $setting = Setting::insert($inserts);

        return response()->json($setting, 200);
    }

}
