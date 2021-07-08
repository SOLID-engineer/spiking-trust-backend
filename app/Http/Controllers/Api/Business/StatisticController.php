<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Review;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function engagement(Request $request)
    {
        $company = $request->get('company');

        $replies_count = Review::with(['reply'])
            ->where('company_id', $company->id)
            ->whereIn('rating', [1, 2])
            ->where('created_at', '>', Carbon::now()->startOfDay()->subMonths(12))
            ->whereHas('reply')->count();

        $reviews = Review::select(['rating', DB::raw('count(*) as count')])
            ->where('company_id', $company->id)
            ->whereIn('rating', [1, 2])
            ->where('created_at', '>', Carbon::now()->startOfDay()->subMonths(12))
            ->groupBy('rating')
            ->get()
            ->pluck('count', 'rating')
            ->all();

        $replies = Review::select(['rating', DB::raw('count(*) as count')])
            ->where('company_id', $company->id)
            ->whereIn('rating', [1, 2])
            ->where('created_at', '>', Carbon::now()->startOfDay()->subMonths(12))
            ->whereHas('reply')
            ->groupBy('rating')
            ->get()
            ->pluck('count', 'rating')
            ->all();

        $average_reply_time = Review::join('review_replies', 'reviews.id', '=', 'review_replies.review_id')
            ->select([DB::raw('avg(UNIX_TIMESTAMP(review_replies.created_at) - UNIX_TIMESTAMP(reviews.created_at)) as average_reply_time')])
            ->where('company_id', $company->id)
            ->whereIn('rating', [1, 2])
            ->where('reviews.created_at', '>', Carbon::now()->startOfDay()->subMonths(12))
            ->first()->average_reply_time;

        $average_reply_time_per_star = Review::join('review_replies', 'reviews.id', '=', 'review_replies.review_id')
            ->select(['rating', DB::raw('avg(UNIX_TIMESTAMP(review_replies.created_at) - UNIX_TIMESTAMP(reviews.created_at)) as average_reply_time')])
            ->where('company_id', $company->id)
            ->whereIn('rating', [1, 2])
            ->where('reviews.created_at', '>', Carbon::now()->startOfDay()->subMonths(12))
            ->groupBy('rating')
            ->get()
            ->pluck('average_reply_time', 'rating')
            ->all();

        return response()->json([
            'reviews' => $reviews,
            'replies' => $replies,
            'replies_count' => $replies_count,
            'average_reply_time_per_star' => $average_reply_time_per_star,
            'average_reply_time' => $average_reply_time
        ]);
    }

    public function periodPerformance(Request $request)
    {
        $company = $request->get('company');
        $star_distribution = Review::where('company_id', $company->id)
            ->where('created_at', '>', Carbon::now()->startOfDay()->subDays(28))
            ->select(['rating', DB::raw('count(*) as count')])
            ->groupBy('rating')
            ->get()
            ->pluck('count', 'rating')
            ->all();

        $review_sources = Review::where('company_id', $company->id)
            ->where('created_at', '>', Carbon::now()->startOfDay()->subDays(28))
            ->select(['source', DB::raw('count(*) as count')])
            ->groupBy('source')
            ->get()
            ->pluck('count', 'source')
            ->all();

        $total_review_count = Review::where('company_id', $company->id)
            ->where('created_at', '>', Carbon::now()->startOfDay()->subDays(28))
            ->count();

        return response()->json([
            'period_start' => Carbon::now()->startOfDay()->subDays(28),
            'period_end' => Carbon::now(),
            'review_sources' => $review_sources,
            'total_review_count' => $total_review_count,
            'star_distribution' => $star_distribution,
        ]);
    }

    public function invitationsOverview(Request $request)
    {
        $company = $request->get('company');
        $current_period = [];
        $results = Invitation::where('company_id', $company->id)
            ->select([
                DB::raw('count(*) as count'),
                DB::raw('date(created_at) as date')
            ])
            ->where('status', Invitation::STATUS_DELIVERED)
            ->where('created_at', '>', Carbon::now()->startOfDay()->subDays(28))
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->all();

        foreach (CarbonPeriod::create(Carbon::now()->subDays(28), Carbon::now()) as $date) {
            $current_period[] = [
                'date' => $date->format('Y-m-d'),
                'delivered' => empty($results[$date->format('Y-m-d')]) ? 0 : $results[$date->format('Y-m-d')]['count']
            ];
        }

        $previous_period_invitation_count = Invitation::where('company_id', $company->id)
            ->where('status', Invitation::STATUS_DELIVERED)
            ->whereBetween('created_at',
                [
                    Carbon::now()->startOfDay()->subDays(57),
                    Carbon::now()->endOfDay()->subDays(29)
                ])
            ->count();

        $current_period_invitation_count = Invitation::where('company_id', $company->id)
            ->where('status', Invitation::STATUS_DELIVERED)
            ->where('created_at', '>', Carbon::now()->startOfDay()->subDays(28))
            ->count();

        return response()->json([
            'current_period' => $current_period,
            'previous_period_invitation_count' => $previous_period_invitation_count,
            'current_period_invitation_count' => $current_period_invitation_count
        ]);
    }

    public function reviewsNumbersOverview(Request $request)
    {
        $company = $request->get('company');

        $current_period = [];
        $results = Review::where('company_id', $company->id)
            ->select([
                DB::raw('count(*) as count'),
                DB::raw('date(created_at) as date')
            ])
            ->where('created_at', '>', Carbon::now()->startOfDay()->subDays(28))
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->all();

        foreach (CarbonPeriod::create(Carbon::now()->subDays(28), Carbon::now()) as $date) {
            $current_period[] = [
                'date' => $date->format('Y-m-d'),
                'review_count' => empty($results[$date->format('Y-m-d')]) ? 0 : $results[$date->format('Y-m-d')]['count']
            ];
        }

        $previous_period_review_count = Review::where('company_id', $company->id)
            ->whereBetween('created_at',
                [
                    Carbon::now()->startOfDay()->subDays(57),
                    Carbon::now()->endOfDay()->subDays(29)
                ])
            ->count();

        $current_period_review_count = Review::where('company_id', $company->id)
            ->where('created_at', '>', Carbon::now()->startOfDay()->subDays(28))
            ->count();

        return response()->json([
            'current_period' => $current_period,
            'previous_period_review_count' => $previous_period_review_count,
            'current_period_review_count' => $current_period_review_count
        ]);
    }
}
