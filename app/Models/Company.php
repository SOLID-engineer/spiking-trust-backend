<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Company
 *
 * @property int $id
 * @property string $domain
 * @property string|null $name
 * @property int $submitted_by
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereSubmittedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Review[] $reviews
 * @property-read int|null $reviews_count
 * @method static \Database\Factories\CompanyFactory factory(...$parameters)
 * @property string|null $claimed_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\CompanyInformation|null $information
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $owners
 * @property-read int|null $owners_count
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereClaimedAt($value)
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUuid($value)
 */
class Company extends Model
{
    use HasFactory, Uuid;

    protected $uuidFields = ['uuid'];

    protected $table = 'companies';

    public function reviews()
    {
        return $this->hasMany(Review::class, 'company_id', 'id');
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'company_owner', 'company_id', 'owner_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'company_category', 'company_id', 'category_id');
    }

    public function information()
    {
        return $this->hasOne(CompanyInformation::class, 'company_id');
    }
}
