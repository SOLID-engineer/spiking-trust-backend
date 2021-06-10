<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function companies(Request $request)
    {
        $user = $request->user();
        $companies = Company::whereHas('owners', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->get();
        return response()->json($companies);
    }
}
