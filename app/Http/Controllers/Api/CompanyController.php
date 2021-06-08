<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PaginateFormatter;
use App\Http\Controllers\Controller;
use App\Mail\ClaimMail;
use App\Models\ClaimToken;
use App\Models\Company;
use App\Models\Review;
use App\Rules\ValidDomain;
use Carbon\Carbon;
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
        $domain = preg_replace("~^www\.~", "", $domain);
        $company = Company::where("domain", $domain)->first();
        if (empty($company)) return response()->json('', 400);
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function claim(Request $request)
    {

        $rules = array(
            'domain' => 'required|between:1,255',
            'email' => 'required'
        );

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) return response()->json('', 400);

        $domain = $request->get('domain');
        $email = $request->get('email');

        $rules = [
            'domain' => ['required', new ValidDomain()]
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $domain = preg_replace("~^www\.~", "", $domain);
        $company = Company::where("domain", $domain)
            ->first();

        if (!$company) {
            $company = new Company();
            $company->domain = $domain;
            $company->name = null;
            $company->save();
            return response()->json($company, 200);
        }

        if ($company->claimed_at) {
            return response()->json([], 402);
        }

        $mail = $email . '@' . $domain;
        $user = $request->user();
        $token = \Hash::make($user->id);

        $claimToken = new ClaimToken();
        $claimToken->user_id = $user->id;
        $claimToken->company_id = $company->id;
        $claimToken->expired_at = Carbon::now()->addWeeks(1);
        $claimToken->token = $token;

        $claimToken->save();

        Mail::to("dangtrungkien96@gmail.com")->send(new ClaimMail($claimToken));

        return response()->json($company, 200);
    }

    public function accept(Request $request)
    {
        $user = $request->user();

        $token = $request->get('token', '');
        $company_id = $request->get('company_id', '');
        $claimToken = ClaimToken::where('company_id', $company_id)
            ->where('expired_at', '>=', Carbon::now())->orderBy('id', 'desc')->first();
        if (!$claimToken) return response()->json([], 400);
        if ($claimToken->user_id != $user->id) return response()->json([], 401);
        if ($token !== $claimToken->token) return response()->json([], 400);

        return response()->json([], 200);
    }

    public function domainClaim(Request $request)
    {
        $company_id = $request->get('company_id', '');

        $company = Company::where('id', $company_id)
            ->whereNull('claimed_at')
            ->with(['claimToken' => function ($query) {
                return $query->select('expired_at', 'user_id', 'company_id')
                    ->where('expired_at', '>=', Carbon::now())
                    ->orderBy('id', 'desc')
                    ->first();
            }])
            ->orderBy('id', 'desc')->first();

        if (empty($company)) return response()->json([], 400);
        if (empty($company->claimToken)) return response()->json([], 400);

        return response()->json($company, 200);
    }
}
