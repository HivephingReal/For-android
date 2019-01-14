<?php

namespace App\Http\Middleware;

use App\Invest;
use Closure;
use Illuminate\Support\Facades\Auth;

class investype
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
        if (Auth::user()->type != 2 ){
            return redirect('error');
        }else {
            $inc=Invest::where('user_id',Auth::user()->id)->count();
            if($inc == 0){
                return redirect('inves_require');
            }else {
                return $next($request);
            }
        }
    }
}
