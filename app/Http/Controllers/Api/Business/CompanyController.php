<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function reviewStatistics(Request $request)
    {
        $company = $request->get('company');
        $from = $request->get('from', Carbon::now()->startOfDay()->toIso8601String());
        $to = $request->get('to', Carbon::now()->endOfDay()->toIso8601String());
        $query = Review::where('company_id', $company->id);
        $query->whereTime('created_at', '>=', Carbon::parse($from));
        $query->whereTime('created_at', '<=', Carbon::parse($to));
        $reviews_count = $query->count();
        $data = [
            'reviews_count' => $reviews_count,
            'verified_reviews_count' => 0,
            'stars' => $query->get()->countBy('rating')
        ];
        return $data;
    }
}
