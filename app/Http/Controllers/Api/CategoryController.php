<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PaginateFormatter;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function category($slug, Request $request)
    {
        $category = Category::where([['status', 1], ['slug', $slug]])
            ->with(['children' => function ($query) {
                return $query->where('status', 1);
            }])
            ->with('parent')
            ->first();

        if ($category->children->isEmpty()) {
            $category = Category::where([['status', 1], ['id', $category->parent_id]])
                ->with(['children' => function ($query) {
                    return $query->where('status', 1);
                }])
                ->with('parent')
                ->first();
        }
        return response($category, 200);
    }

    public function getCompanyByCategory($slugCategory, Request $request)
    {
        $categories = [];
        $category = Category::where([['status', 1], ['slug', $slugCategory]])
            ->first();

        $depth = $category->depth;
        $categories[] = $category->id;
        $categoryHasChildren = Category::where([['status', 1], ['depth', 'like', "$depth/%"]])
            ->get();
        if ($categoryHasChildren->isNotEmpty()) {
            $categoriesChildren = $categoryHasChildren->pluck('id')->all();
            $categories = array_merge($categoriesChildren);

        }
        $companies = Company::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->with('categories')
            ->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('category_id', $categories);
            })->paginate(10);
        /** @var LengthAwarePaginator $companies */
        $results = PaginateFormatter::format($companies);

        return response($companies, 200);
    }
}
