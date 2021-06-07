<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ClaimMail;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
        $company->average_rating = $company->reviews()->avg('rating') ?? 0;
        $company->reviews_count = $company->reviews()->count();
        return response()->json($company, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function claim(Request $request) {

        $rules = array(
            'domain'    => 'required|between:1,255',
            'email' => 'required'
        );

        $validate =  Validator::make($request->all(), $rules);
        if ($validate->fails()) return response()->json('', 400);

        $domain = $request->get('domain');
        $email = $request->get('email');

        $mail = $email.'@'.$domain;

        Mail::to($mail)->send(new ClaimMail());

        return response()->json([], 200);
    }
}
