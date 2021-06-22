<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\PaginateFormatter;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $company = $request->get('company');
        $perPage = $request->get('perPage', 5);
        $query = Review::with(['author:id,first_name,last_name', 'reply']);
        $query->where('company_id', $company->id);

        $search = $request->get('search');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")->orWhere('content', 'like', "%$search%");
            });
        }

        $reply = $request->get('reply');
        if (!empty($reply)) {
            if ($reply === 'true') $query->whereHas('reply');
            else $query->whereDoesntHave('reply');
        }

        $stars = $request->get('stars');
        if (!empty($stars)) {
            if (is_array($stars)) $query->whereIn('rating', $stars);
            else $query->where('rating', $stars);
        }

        $query->orderByDesc('created_at');
        $reviews = $query->paginate($perPage);
        return response()->json(PaginateFormatter::format($reviews));
    }

}
