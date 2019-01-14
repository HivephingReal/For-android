<?php

namespace App\Http\Middleware;

use App\AssoModel;
use Closure;
use Illuminate\Support\Facades\Auth;

class Asso
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
        if (Auth::user()->type !=3){
            return redirect('error');
        }else {
            $inc=AssoModel::where('user_id',Auth::user()->id)->count();
            if($inc == 0){
                return redirect('asso/asso_register');
            }else {
                return $next($request);
            }
        }
    }
}
