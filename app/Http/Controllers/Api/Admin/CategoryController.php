<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $level = $request->input('level', '3');

        $category_id = $request->input('category_id', null);

        $categoryModel = Category::where('level', "<=", $level);

        if ($category_id) {
            $categoryModel->where('id', '!=', $category_id);
            $categoryModel->where('parent_id', '!=', $category_id);
        }

        $categories = $categoryModel->with('children')
            ->with('parent')
            ->orderBy('depth', 'asc')->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $validate = $this->_validate($request->all());
        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }
        $user = $request->user();
        DB::beginTransaction();
        try {
            $category = new Category();
            $category->parent_id = $request->parent_id;
            $category->status = $request->status;
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->created_by = $user->id;
            $category->updated_by = $user->id;
            $category->save();
            $category = $this->_updateParent($category->id, $category->parent_id);
            DB::commit();
            return response()->json($category);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([], 400);
        }
    }

    public function edit(Request $request, $id)
    {
        $category = Category::find($id);

        return response()->json($category, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request, $id)
    {
        $validate = $this->_validate($request->all());
        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }
        $category = Category::find($id);

        if (!$category) {
            return response()->json([], 404);
        }
        $user = $request->user();

        DB::beginTransaction();
        try {
            $category->parent_id = $request->parent_id;
            $category->status = $request->status;
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->updated_by = $user->id;
            $category->save();
            $category = $this->_updateParent($category->id, $category->parent_id);
            DB::commit();
            return response()->json($category);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $categoryModel = Category::find($id);
            $hasChildren = Category::where('parent_id', $id)
                ->get();

            if ($hasChildren->isNotEmpty()) {
                return response()->json(['msg' => 'Can not delete record. Category has children.'], 400);
            }
            $categoryModel->delete();
            DB::commit();
            return response()->json([], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => false], 400);
        }
    }

    public function _updateParent($id, $parent_id = 0)
    {
        $categoryModel = Category::find($id);

        if ($parent_id == 0) {
            $categoryModel->depth = $id;
            $categoryModel->level = 1;
        } else {
            $categoryParent = Category::find($parent_id);
            $categoryModel->depth = $categoryParent->depth . "/" . $id;
            $categoryModel->level = count(explode('/', $categoryModel->depth));
        }
        $categoryModel->save();

        return $categoryModel;
    }


    public function _validate($request)
    {
        $rules = array(
            'name' => 'between:1,255',
            'parent_id' => 'required'
        );

        return Validator::make($request, $rules);
    }
}
