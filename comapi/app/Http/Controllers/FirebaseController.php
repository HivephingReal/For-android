<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class FirebaseController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    public function store_token(Request $request){
       $user_id=JWTAuth::user()->id;
       $token=$request->firebase_token;
     $old_token=DB::table('firebase')->where('user_id',JWTAuth::user()->id);
        if($old_token->count() > 0){
            if($old_token->first()->token == $token){

            }else{
                $old_token->update(['user_id'=>$user_id,'token'=>$token,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
            }
        }else{
            DB::table('firebase')->create(['user_id'=>$user_id,'token'=>$token,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        }
        return response()->json(['note'=>'token stored']);

    }
}