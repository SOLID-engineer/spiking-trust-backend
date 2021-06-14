<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ClaimToken
 *
 * @property int $id
 * @property int $user_id
 * @property int $company_id
 * @property string $token
 * @property string $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken whereUserId($value)
 * @mixin \Eloquent
 * @property string $domain
 * @property string $email
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClaimToken whereEmail($value)
 */
class ClaimToken extends Model
{
    use HasFactory;

    protected $table = 'claim_tokens';

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

}
