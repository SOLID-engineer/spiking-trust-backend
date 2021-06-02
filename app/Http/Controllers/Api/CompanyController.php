<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
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
        $domain = preg_replace ("~^www\.~", "", $domain);
        $company = Company::where("domain", $domain)->first();
        $company->average = $company->reviews()->avg('rating');
        $company->count = $company->reviews()->count();
        return response()->json($company, 200);
    }
}
