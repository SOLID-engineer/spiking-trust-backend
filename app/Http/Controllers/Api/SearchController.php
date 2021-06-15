<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PaginateFormatter;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Rules\ValidDomain;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $results = [];
        $query = $request->get('query', '');

        if (!empty($query)) {
            $companies = Company::withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where("domain", "like", "%$query%")
                ->paginate(10);
            /** @var LengthAwarePaginator $companies */
            $results = PaginateFormatter::format($companies);
        }

        return response()->json($results, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function suggestion(Request $request)
    {
        $results = [];
        $query = $request->get('query', '');
        if (!empty($query)) {
            $results['companies'] = Company::where("domain", "like", "%$query%")
                ->limit(5)->get();
            $results['categories'] = [];
        }

        return response()->json($results, 200);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createDomain(Request $request)
    {
        $rules = [
            'domain' => ['required', new ValidDomain()]
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $domain = $request->get('domain', '');
        $domain = preg_replace("~^www\.~", "", $domain);
        $company = Company::where("domain", "$domain")
            ->first();

        if (!empty($company)) {
            return response()->json($company, 200);
        }
        if (gethostbyname($domain) != $domain) {
            $company = new Company();
            $company->domain = $domain;
            $company->name = null;
            $company->save();

            return response()->json($company, 200);
        }

        return response()->json(['domain' => 'Oops something went wrong'], 422);
    }
}
