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
 * @property int $company_id
 * @property string|null $company_name
 * @property string|null $email
 * @property string|null $telephone
 * @property string|null $street_address
 * @property string|null $zip_code
 * @property string|null $city
 * @property string|null $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereStreetAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereZipCode($value)
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
