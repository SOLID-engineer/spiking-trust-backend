<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request)
    {
        $categories = Category::with(['children', 'parent'])->get();

        return response()->json($categories, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $company = $request->get('company');
        $categories = Category::with('company')->whereHas('companies', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->get();

        return response()->json($categories, 200);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $category = $request->get('category', '');
        if (empty($category)) {
            return response()->json([], 400);
        }
        $category = Category::with('children')->find($category);

        if ($category->status != 1 || !$category->children->isEmpty()) {
            return response()->json([], 400);
        }
        $company = Company::with('categories')->find($request->company->id);
        if (!empty($company['categories']) && count($company['categories']) > 6) {
            return response()->json([], 400);
        }
        DB::beginTransaction();
        try {
            if ($company->categories->isNotEmpty()) {
                $request->company->categories()->attach($category);
            } else {
                $request->company->categories()->attach($category, ['is_primary' => 1]);
            }
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
    public function delete(Request $request)
    {
        $category = $request->get('category', '');
        if (empty($category)) {
            return response()->json([], 400);
        }
        DB::beginTransaction();
        try {
            $isPrimary = CompanyCategory::where([['category_id', $category], ['company_id', $request->company->id]])->first();
            $request->company->categories()->detach($category);
            if ($isPrimary) {
                $company = CompanyCategory::where([['is_primary', 0], ['company_id', $request->company->id]])->first();
                CompanyCategory::where([['category_id', $company->category_id], ['company_id', $request->company->id]])
                            ->update(['is_primary' => 1]);
            }
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
    public function setDefault(Request $request)
    {
        $category = $request->get('category', '');
        if (empty($category)) {
            return response()->json([], 400);
        }
        DB::beginTransaction();
        try {

            CompanyCategory::where([['company_id', $request->company->id], ['is_primary', 1]])->update([
                'is_primary' => 0
            ]);
            CompanyCategory::where([['category_id', $category], ['company_id', $request->company->id]])->update([
                'is_primary' => 1
            ]);;
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([], 500);
        }
        return response()->json([], 200);
    }
}
