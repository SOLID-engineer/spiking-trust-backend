<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CompanyCategory
 *
 * @property int $company_id
 * @property int $category_id
 * @property int $is_primary
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyCategory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyCategory whereIsPrimary($value)
 * @mixin \Eloquent
 */
class CompanyCategory extends Model
{
    use HasFactory;

    protected $table = 'company_category';

    public $timestamps = false;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,  'category_id');
    }
}
