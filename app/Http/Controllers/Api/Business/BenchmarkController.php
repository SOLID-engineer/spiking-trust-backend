<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\Benchmark;
use App\Models\Category;
use Illuminate\Http\Request;

class BenchmarkController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->get('company');
        $user = $request->user();
        $rows = Benchmark::where(['user_id' => $user->id, 'business_id' => $company->id])
            ->get()->all();
        return response()->json($rows, 200);
    }

    public function store(Request $request)
    {
        $company = $request->get('company');
        $user = $request->user();
        return response()->json([], 200);
    }
}
