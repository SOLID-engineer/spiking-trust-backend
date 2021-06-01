<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginateFormatter
{
    /**
     * Replaces spaces with full text search wildcards
     * @param LengthAwarePaginator $data
     * @return array
     */
    public static function format($data) {
        $result = [
            'current_page' => $data->currentPage(),
            'per_page' => $data->perPage(),
            'total' => $data->total(),
            'last_page' => $data->lastPage(),
            'items' => $data->items()
        ];

        return $result;
    }
}
