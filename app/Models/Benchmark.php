<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Benchmark
 *
 * @property int $id
 * @property int $user_id
 * @property int $business_id
 * @property string $company_uuid
 * @property int $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Benchmark newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Benchmark newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Benchmark query()
 * @method static \Illuminate\Database\Eloquent\Builder|Benchmark whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Benchmark whereCompanyUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Benchmark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Benchmark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Benchmark wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Benchmark whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Benchmark whereUserId($value)
 * @mixin \Eloquent
 */
class Benchmark extends Model
{
    use HasFactory;
}
