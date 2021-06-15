<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CompanyInformation
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation query()
 * @mixin \Eloquent
 */
class CompanyInformation extends Model
{
    protected $table = 'company_information';
    protected $primaryKey = 'company_id';

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
