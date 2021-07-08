<?php

use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

//    $average_reply_time_per_star = Review::join('review_replies', 'reviews.id', '=', 'review_replies.review_id')
//        ->select(['rating', DB::raw('avg(review_replies.created_at - reviews.created_at) as average_reply_time')])
////        ->where('company_id', $company->id)
//        ->whereIn('rating', [1, 2])
//        ->where('reviews.created_at', '>', Carbon::now()->startOfDay()->subMonths(12))
//        ->groupBy('rating')
//        ->get()
////        ->pluck('average_reply_time', 'rating')
//        ->all();
//
//    Review::with('reply')
//        ->select(['rating', DB::raw('avg(created_at) as average_reply_time')])
////        ->where('company_id', $company->id)
//        ->whereIn('rating', [1, 2])
//        ->where('reviews.created_at', '>', Carbon::now()->startOfDay()->subMonths(12))
//        ->groupBy('rating')
//        ->get()
////        ->pluck('average_reply_time', 'rating')
//        ->all();

//    Review::join('review_replies','reviews.id','=','review_replies.review_id')
//        ->select(['rating', DB::raw('avg(review_replies.created_at - reviews.created_at) as average_reply_time')])
////        ->where('company_id', $company->id)
//        ->whereIn('rating', [1, 2])
//        ->where('reviews.created_at', '>', Carbon::now()->startOfDay()->subMonths(12))
//        ->groupBy('rating')
//        ->get()
//            ->pluck('count', 'rating')
//        ->all();
//
//    $current_period = Review::where('company_id', 84)
//        ->select([
//            DB::raw('count(*) as count'),
//            DB::raw('date(created_at) as date')
//        ])
//        ->where('created_at', '>', Carbon::now()->startOfDay()->subDays(28))
//        ->groupBy('date')
//        ->get()
//        ->all();
//
//    $previous_period_review_count = Review::where('company_id', 84)
//        ->whereBetween('created_at',
//            [
//                Carbon::now()->startOfDay()->subDays(57),
//                Carbon::now()->endOfDay()->subDays(29)
//            ])
//        ->count();
//
//    $current_period_review_count = Review::where('company_id', 84)
//        ->where('created_at', '>',Carbon::now()->startOfDay()->subDays(28))
//        ->count();

//    dd(\Carbon\CarbonPeriod::create(Carbon::now()->subDays(28), Carbon::now())->toArray());

    return view('welcome');
});
