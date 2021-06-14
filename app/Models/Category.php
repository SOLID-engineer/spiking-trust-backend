<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property string|null $url_image
 * @property int $parent_id
 * @property string $depth
 * @property int|null $position
 * @property string $status
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUrlImage($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasFactory;

    public function children () {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function parent () {
        return $this->hasOne(Category::class, 'id', 'parent_id');
    }

    public function companies() {
        return $this->belongsToMany(Company::class, 'company_category', 'category_id', 'company_id');
    }

    public function company() {
        return $this->hasOne(CompanyCategory::class, 'category_id', 'id');
    }
}
