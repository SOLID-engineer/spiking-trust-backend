<?php

namespace App\Helper\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait PaginateFormatter
{
    /**
     * Replaces spaces with full text search wildcards
     * @param LengthAwarePaginator $data
     * @return array
     */
    public static function formatter($data, $keyValue = 'data') {
        $result = [
            'current_page' => $data->currentPage(),
            'per_page' => $data->perPage(),
            'total' => $data->total(),
            'last_page' => $data->lastPage()
        ];
        $result[$keyValue] = $data->items();

        return $result;
    }
}
