<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class Block
{
 public function handle($request, Closure $next)
    {
        $user = DB::table('user_block')->where([['user_id', '=', Auth::user()->id], ['circum', '=', 'block']]);
        $com = DB::table('company')->where('user_id', Auth::user()->id);
        $no_user=DB::table('user_block')->where([['user_id', '=', Auth::user()->id]]);
        if($no_user->count()== 0){
            return response()->json(['note'=>'block']);

        }else {
            if ($com->count() > 0) {
                $check_second_data = $com->first()->investment;
                if (empty($check_second_data)) {
                    $comtf = 'false';
                } else {
                    $comtf = 'true';
                }
            } else {
                $comtf = 'false';
            }

            if ($user->count() > 0 and $comtf == 'true') {
                return response()->json(['note'=>'block']);
            }
        }

        return $next($request);
    }
}
