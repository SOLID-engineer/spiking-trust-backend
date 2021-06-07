<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PaginateFormatter;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * @param Request $request
     * @param $domain
     * @return JsonResponse
     */
    public function index(Request $request, $domain)
    {
        $domain = preg_replace("~^www\.~", "", $domain);
        $company = Company::where("domain", $domain)->first();
        $company->average_rating = $company->reviews()->avg('rating') ?? 0;
        $company->reviews_count = $company->reviews()->count();
        return response()->json($company, 200);
    }

    /**
     * @param Request $request
     * @param $domain
     * @return JsonResponse
     */
    public function reviews(Request $request, $domain)
    {
        $domain = preg_replace("~^www\.~", "", $domain);
        $company = Company::where("domain", $domain)->first();
        $reviews = Review::with(['author:id,first_name,last_name'])
            ->where('company_id', $company->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        $results = PaginateFormatter::format($reviews);
        return response()->json($results, 200);
    }
}
