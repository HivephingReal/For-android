<?php

namespace App\Http\Middleware;

use App\RegisterfeeModel;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class Registerfee
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
        $has=RegisterfeeModel::where('user_id',Auth::user()->id)->first();
        $has_count=RegisterfeeModel::where('user_id',Auth::user()->id)->count();
        if($has_count == 0){
            return redirect('balance_not_enough');
        }
        if($has->amount < 37){
            return redirect('balance_not_enough');
        }
        if($has->expire_date < Carbon::now()){
            return redirect('balance_not_enough');

        }
        return $next($request);
    }
}
