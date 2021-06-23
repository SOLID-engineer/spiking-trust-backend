<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\Benchmark;
use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $company = $request->get('company');
        $uuid = $request->route()->parameter('uuid');

        $review = Review::where('uuid', $uuid)->first();
        if (empty($review) || $review->company_id !== $company->id) abort(404);

        $rules = ['content' => ['required']];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) abort(400);

        $model = new ReviewReply();
        $model->review_id = $review->id;
        $model->content = $request->post('content');
        $model->save();

        return response()->json($model);
    }

    public function destroy(Request $request)
    {
        $company = $request->get('company');
        $uuid = $request->route()->parameter('uuid');

        $review = Review::where('uuid', $uuid)->first();
        if (empty($review) || $review->company_id !== $company->id) abort(404);

        ReviewReply::where('review_id', $review->id)->delete();
        return response()->json([]);
    }
}
