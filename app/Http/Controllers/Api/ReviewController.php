<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store($domain, Request $request)
    {
        $rules = [
            'rating' => ['required', 'numeric'],
            'content' => ['required', 'max:500'],
            'title' => ['required', 'max:255'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return response()->json('', 400);

        $domain = preg_replace("~^www\.~", "", $domain);
        $company = Company::where("domain", $domain)->first();
        if (empty($company)) return response()->json('', 400);

        $params = $request->all();
        $review = new Review();
        $review->rating = $params['rating'];
        $review->content = $params['content'];
        $review->title = $params['title'];
        $review->author_id = 1;
        $review->company_id = $company->id;
        $review->ip_address = $request->ip();
        $review->save();

        $review->offsetUnset('id');

        return response()->json($review, 200);
    }
}
