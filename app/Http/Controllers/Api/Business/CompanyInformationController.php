<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\CompanyInformation;
use Illuminate\Http\Request;

class CompanyInformationController extends Controller
{
    public function show(Request $request)
    {
        $company = $request->get('company');
        return response()->json($company->information);
    }

    public function update(Request $request)
    {
        $company = $request->get('company');
        $rules = [
            'company_name' => ['max:255'],
            'email' => ['max:255'],
            'telephone' => ['max:255'],
            'street_address' => ['max:255'],
            'zip_code' => ['max:255'],
            'city' => ['max:255'],
            'country' => ['max:255'],
        ];
        $validator = \Validator::make($request->post(), $rules);
        if ($validator->fails()) return response()->json('', 400);
        $information = $company->information;
        if (empty($information)) {
            $information = new CompanyInformation();
            $information->company_id = $company->id;
        }
        $information->company_name = $request->post('company_name');
        $information->email = $request->post('email');
        $information->telephone = $request->post('telephone');
        $information->street_address = $request->post('street_address');
        $information->zip_code = $request->post('zip_code');
        $information->city = $request->post('city');
        $information->country = $request->post('country');
        $information->save();
        return response()->json($information);
    }
}
