<?php

namespace App\Http\Middleware;

use App\Company;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class NeedtoCompanyRegister
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $company_count = Company::where('user_id', Auth::user()->id)->count();
        if ($company_count == 0) {
            return response()->json(['firstform'=>'comreg_firstform','note'=>'need to fill com data first']);
        }
        $company_data = Company::where('user_id', Auth::user()->id)->first();
        if($company_data->status < 2) {

            if ($company_data->status == 1) {

                return response()->json(['secondform'=>'comreg_secondform','note'=>'need to fill com data second']);
            } elseif ($company_data->status == 2) {
                return response()->json(['third'=>'comreg_thirdform','note'=>'need to fill com data third']);

            }

        }else {
            return $next($request);
        }
    }
}
