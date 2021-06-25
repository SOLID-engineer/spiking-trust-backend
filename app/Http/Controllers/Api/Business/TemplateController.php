<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\MailTemplate;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->get('company');
        $type = $request->get('type');
        $templates = MailTemplate::where('type', $type)
            ->select(['uuid', 'name', 'is_default'])
            ->get()
            ->all();
        return response()->json($templates);
    }

    public function show(Request $request)
    {
        $company = $request->get('company');
        $uuid = $request->route()->parameter('uuid');
        $template = MailTemplate::where('uuid', $uuid)
            ->select(['uuid', 'name', 'subject', 'content'])
            ->first();
        if (empty($template)) response()->json('', 404);
        return response()->json($template);
    }
}
