<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\PaginateFormatter;
use App\Http\Controllers\Controller;
use App\Models\MailTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('prePage', 20);

        $companies = MailTemplate::orderByDesc('created_at')
            ->paginate($perPage);

        /** @var LengthAwarePaginator $companies */
        $results = PaginateFormatter::format($companies);

        return response()->json($results, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $rules = array(
            'subject' => 'required|max:256',
            'name' => 'required|max:256',
            'content' => 'required',
            'type' => 'required',
        );
        $validate = \Validator::make($request->all(), $rules);
        if ($validate->fails()) return response()->json([], 400);

        $subject = $request->get('subject');
        $name = $request->get('name');
        $content = $request->get('content');
        $type = $request->get('type');

        $is_default = $this->isPrimary($request->get('is_default'), $type);

        $mailTemplate = new MailTemplate();
        $mailTemplate->name = $name;
        $mailTemplate->subject = $subject;
        $mailTemplate->content = $content;
        $mailTemplate->type = $type;
        $mailTemplate->is_default = $is_default;
        $mailTemplate->save();

        return response()->json($mailTemplate, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        $template = MailTemplate::find($id);

        return response()->json($template, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'subject' => 'required|max:256',
            'name' => 'required|max:256',
            'content' => 'required',
            'type' => 'required',
        );
        $validate = \Validator::make($request->all(), $rules);
        if ($validate->fails()) return response()->json([], 400);

        $subject = $request->get('subject');
        $name = $request->get('name');
        $content = $request->get('content');
        $type = $request->get('type');

        $is_default = $this->isPrimary($request->get('is_default'), $type);

        $mailTemplate = MailTemplate::find($id);
        $mailTemplate->name = $name;
        $mailTemplate->subject = $subject;
        $mailTemplate->content = $content;
        $mailTemplate->type = $type;
        $mailTemplate->is_default = $is_default;
        $mailTemplate->save();

        return response()->json($mailTemplate, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $review = MailTemplate::find($id);
        $review->delete();

        return response()->json([], 200);
    }

    public function isPrimary($is_default, $type, $id = null)
    {
        if (!$is_default) {
            $template = MailTemplate::where(['is_default' => 1, 'type' => $type]);
            if ($id) {
                $template->where('id', '!=', $id);
            }
            $template->first();

            return empty($template) ? false : true;
        }

        MailTemplate::where(['type' => $type])
            ->update(['is_default' => 0]);

        return true;
    }
}
