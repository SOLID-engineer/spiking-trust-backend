<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function mailInvitation() {
        $setting = Setting::where('type', Setting::MAIL_INVITATION)->first();

        return response()->json($setting, 200);
    }

    public function saveMailInvitation(Request $request) {
        $template = $request->get('template', '');

        $inserts[] = [
            "key" => Setting::MAIL_INVITATION,
            "value" => $template,
            "type" => Setting::MAIL_INVITATION,
        ];

        Setting::upsert($inserts, ['key'], ['value']);

        return response()->json($inserts, 200);
    }
}
