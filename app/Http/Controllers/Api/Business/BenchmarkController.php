<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\Benchmark;
use Illuminate\Http\Request;

class BenchmarkController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->get('company');
        $user = $request->user();
        $rows = Benchmark::where([
            'user_id' => $user->id,
            'business_id' => $company->id
        ])->orderBy('position', 'desc')->get()->all();
        return response()->json($rows);
    }

    public function store(Request $request)
    {
        $business = $request->get('company');
        $company_uuid = $request->post('company_uuid');
        $user = $request->user();
        $model = new Benchmark();
        $model->user_id = $user->id;
        $model->business_id = $business->id;
        $model->company_uuid = $company_uuid;
        $model->save();
        return response()->json($model);
    }

    public function destroy(Request $request)
    {
        $business = $request->get('company');
        $user = $request->user();
        $uuid = $request->route()->parameter('uuid');
        Benchmark::where([
            ['user_id', $user->id],
            ['business_id', $business->id],
            ['company_uuid', $uuid]
        ])->delete();
        return response()->json([]);
    }

    public function updatePositions(Request $request)
    {
        $business = $request->get('company');
        $user = $request->user();
        $positions = $request->post('positions');
        if (!empty($positions) && is_array($positions)) {
            foreach ($positions as $r) {
                Benchmark::where([
                    ['user_id', $user->id],
                    ['business_id', $business->id],
                    ['company_uuid', $r['uuid']]
                ])->update(['position' => $r['position']]);
            }
        }
        return response()->json($positions);
    }
}
