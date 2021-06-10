<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index (Request $request) {
        $company = $request->get('company');
        $categories = Category::whereHas('companies', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->get();

        return response()->json($categories, 200);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store (Request $request) {
        $category = $request->get('category', '');
        if (empty($category)) {
            return response()->json([], 400);
        }
        $category = Category::with('children')->find($category);
        if ($category->status != 1 || !$category->children->isEmpty()) {
            return response()->json([], 400);
        }
        DB::beginTransaction();
        try {
            $request->company->categories()->attach($category);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([], 500);
        }
        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function delete(Request $request) {
        $category = $request->get('category', '');
        if (empty($category)) {
            return response()->json([], 400);
        }
        DB::beginTransaction();
        try {
            $request->company->categories()->detach($category);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([], 500);
        }
        return response()->json([], 200);
    }
}
