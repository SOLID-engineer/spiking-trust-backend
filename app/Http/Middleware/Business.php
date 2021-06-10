<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;

class Business
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $domain = $request->route()->parameter('domain');
        if (!empty($domain)) {
            $company = Company::where('domain', $domain)->first();
            if (empty($company)) abort(404);
            $request->request->set('company', $company);
        }
        return $next($request);
    }
}
