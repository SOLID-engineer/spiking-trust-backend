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

    public function index(Request $request) {

        $category = Category::where('status', 1)->get();

        return response($category, 200);
    }
}
