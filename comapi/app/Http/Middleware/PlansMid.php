<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use App\Plans;
use Illuminate\Support\Facades\Auth;

class PlansMid
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


        if(Carbon::parse(Auth::user()->created_at)->addDays(15) > Carbon::now()){
            return $next($request);
        }
        $get_plan=Plans::where('user_id',Auth::user()->id);


        if($get_plan->count() == 0){
            return redirect('balance_not_enough');
        }
        if($get_plan->first()->expire_date < Carbon::now()){
            Plans::where('user_id',Auth::user()->id)->delete();
            return redirect('expire');

        }
        return $next($request);
    }
}
