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
use Illuminate\Support\Facades\DB;
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
        if (empty($company)) abort(404);
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
     * @throws \Throwable
     */
    public function claim(Request $request)
    {
        $rules = array(
            'domain' => ['required', 'between:1,255', new ValidDomain()],
            'email' => 'required'
        );

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) return response()->json([], 400);

        $domain = $request->get('domain');
        $email = $request->get('email');
        $domain = preg_replace("~^www\.~", "", $domain);
        $company = Company::where("domain", $domain)
            ->first();

        if ($company && $company->claimed_at) {
            return response()->json([], 402);
        }
        DB::beginTransaction();
        try {
            $mail = $email . '@' . $domain;
            $user = $request->user();
            $token = \Hash::make($user->id);

            $claimToken = new ClaimToken();
            $claimToken->user_id = $user->id;
            $claimToken->domain = $domain;
            $claimToken->email = $mail;
            $claimToken->expired_at = Carbon::now()->addWeeks(1);
            $claimToken->token = $token;
            $claimToken->save();
            $mailData = [
                'name' => $user->first_name,
                'domain' => $domain,
                'token' => $token,
            ];
            Mail::to($mail)->cc(['dangtrungkien96@gmail.com', 'hieu.sen107@gmail.com'])->send(new ClaimMail($mailData));
            DB::commit();
            return response()->json($company, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function accept(Request $request)
    {
        $user = $request->user();
        $token = $request->get('token', '');

        $claimToken = ClaimToken::where('token', $token)
            ->orderBy('id', 'desc')->first();

        if (!$claimToken) return response()->json(['message' => 'Token is not exist'], 400);
        if (Carbon::parse($claimToken->expired_at)->timestamp < Carbon::now()->timestamp)
            return response()->json(['message' => 'The token is expired'], 400);
        if ($claimToken->user_id != $user->id) return response()->json([], 403);
        DB::beginTransaction();
        try {
            $company = Company::where("domain", $claimToken->domain)
                ->first();
            if (!$company) {
                $company = new Company();
                $company->domain = $claimToken->domain;
                $company->name = null;
                $company->claimed_at = Carbon::now();
                $company->save();
                $company->owners()->attach($claimToken->user_id);
            } else {
                $company->claimed_at = Carbon::now();
                $company->owners()->attach($claimToken->user_id);
                $company->save();
            }
            $claimToken->expired_at = Carbon::now();
            $claimToken->save();
            DB::commit();
            return response()->json([], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([], 500);
        }
    }

}
