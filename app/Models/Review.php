<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Review
 *
 * @property int $id
 * @property int $rating
 * @property string $title
 * @property string $content
 * @property int $company_id
 * @property int $author_id
 * @property string $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User|null $author
 * @method static \Database\Factories\ReviewFactory factory(...$parameters)
 */
class Review extends Model
{
    use HasFactory, Uuid;

    protected $uuidFields = ['uuid'];

    protected $table = 'reviews';

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }
}
