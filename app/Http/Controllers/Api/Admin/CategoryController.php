<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $orderName = $request->input('orderName', 'id');
        $orderBy   = $request->input('orderBy', 'desc');

        $categories = Category::orderBy($orderName, $orderBy)
                                ->get();

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
        if (!$validate) {
            return response()->json($validate->errors(), 400);
        }
        $user = $request->user();
        DB::beginTransaction();
        try {
            $category = new Category();
            $category->parent_id = $request->parent_id;
            $category->status = $request->status;
            $category->name = $request->name;
            $category->slug = $request->name;
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
        if (!$validate) {
            return response()->json($validate->errors(), 400);
        }
        $category = Category::find($id);

        if (!$category) {
            return response()->json($validate->errors(), 404);
        }
        $user = $request->user();

        DB::beginTransaction();
        try {
            $category->parent_id = $request->parent_id;
            $category->status = $request->status;
            $category->name = $request->name;
            $category->slug = $request->name;
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
            $hasParent   = Category::where('parent_id', $id)
                                    ->get();

            if ($hasParent->isEmpty()) {
                $categoryModel->delete();
                DB::commit();
                return response()->json([], 200);
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => false], 400);
        }
    }

    public function _updateParent($id, $parent_id = 0) {
        $categoryModel  = Category::find($id);
        if ($parent_id == 0) {
            $categoryModel->depth = $id;
        } else {
            $categoryParent       = Category::find($parent_id);
            $categoryModel->depth = $categoryParent->depth."/".$id;
        }
        $categoryModel->save();

        return $categoryModel;
    }


    public function _validate($request) {
        $rules = array(
            'name'    => 'between:1,255',
            'parent_id' => 'required'
        );

        return Validator::make($request, $rules);
    }
}
