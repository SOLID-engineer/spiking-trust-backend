<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */

    public function index(Request $request)
    {
        $categories = Category::where([['status', 1], ['level', "<=", 2]])
            ->get();
        $_categories = [];
        foreach ($categories as $category) {
            if ($category['parent_id'] === 0) {
                $category['children'] = [];
                foreach ($categories as $children) {
                    if ($category['id'] === $children['parent_id']) {
                        $category['children'] = array_merge($category['children'], [$children]);
                    }
                }
                $_categories[] = $category;
            }
        }
        return response($_categories, 200);
    }

    public function category ($slug, Request $request) {
        $category = Category::where([['status', 1], ['slug', $slug]])
                            ->with(['children' => function($query) {
                                return $query->where('status', 1);
                            }])
                            ->with('parent')
                            ->first();

        if ($category->children->isEmpty()) {
            $category = Category::where([['status', 1], ['id', $category->parent_id]])
                ->with(['children' => function($query) {
                    return $query->where('status', 1);
                }])
                ->with('parent')
                ->first();
        }

        return response($category, 200);
    }
}
