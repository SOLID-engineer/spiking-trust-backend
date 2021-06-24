<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\PaginateFormatter;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('prePage', 20);

        $reviews = Review::with('company', 'author')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        /** @var LengthAwarePaginator $reviews */
        $results = PaginateFormatter::format($reviews);

        return response()->json($results, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        $review = Review::find($id);

        return response()->json($review, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'rating' => ['required', 'numeric'],
            'content' => ['required'],
            'title' => ['required', 'max:255'],
        ];
        $validate = \Validator::make($request->all(), $rules);
        if ($validate->fails()) return response()->json([], 400);

        $review = Review::find($id);

        $params = $request->all();

        $review->rating = $params['rating'];
        $review->content = $params['content'];
        $review->title = $params['title'];
        $review->save();
        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id) {
        $review  = Review::find($id);
        $review->delete();

        return response()->json([], 200);
    }
}
