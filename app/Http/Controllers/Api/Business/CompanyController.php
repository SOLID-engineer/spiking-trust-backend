<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $companies = Company::whereHas('owners', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->get();
        return response()->json($companies);
    }

    public function show(Request $request)
    {
        $company = $request->get('company');
        $reviews = $company->reviews();
        $company->average_rating = $reviews->avg('rating') !== null ? $reviews->avg('rating') : 0;
        $company->reviews_count = $reviews->count();
        return response()->json($company);
    }

    public function update(Request $request)
    {
        $company = $request->get('company');
        $rules = [
            'name' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
        ];
        $validator = \Validator::make($request->post(), $rules);
        if ($validator->fails()) return response()->json('', 400);

        $company->name = $request->post('name');
        $company->description = $request->post('description');
        $company->save();

        return response()->json($company);
    }

    public function reviewStatistics(Request $request)
    {
        $company = $request->get('company');
        $from = $request->get('from');
        $to = $request->get('to');
        $query = Review::where('company_id', $company->id);
//        $query->whereTime('created_at', '>=', Carbon::parse($from));
//        $query->whereTime('created_at', '<=', Carbon::parse($to));
        $reviews_count = $query->count();
        $replied_reviews_count = $query->whereHas('reply')->count();
        $data = [
            'reviews_count' => $reviews_count,
            'replied_reviews_count' => $replied_reviews_count,
            'verified_reviews_count' => 0,
            'stars' => Review::where('company_id', $company->id)->select(['rating', DB::raw('count(*) as count')])->groupBy('rating')->get()->pluck('count', 'rating')->all()
        ];
        return $data;
    }

    public function logo(Request $request)
    {
        $company = $request->get('company');
        $request->validate(['image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
        if (!$request->hasFile('image')) {
            return response()->json('', 400);
        }
        $image = $request->file('image');
        $path = Storage::disk('public')->putFile('company-profile-image', $image);
        $company->profile_image = $path;
        $company->save();
        return response()->json($company);
    }
}
