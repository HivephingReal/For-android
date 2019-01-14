<?php

namespace App\Http\Controllers;

use App\FreePlan;
use App\Freeplanforuser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterforcomuserapiController extends Controller
{
    //
    public function _construct(){

    }
    public function register(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',

        ]);

        if($validator->fails())
        {
            return response()->json(['success' => false, 'data'=> $validator->errors()]);

        }
        $data=$request->all();
        $current_user_data=User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'permission'=>'true',
            'role'=>'user',
            'type'=>1,
        ]);
        $free_plan_data=FreePlan::where('id',1)->first();
        DB::table('user_block')->insert(['user_id'=>$current_user_data->id,'admin_id'=>6,'circum'=>'block','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        Freeplanforuser::create(['user_id'=>$current_user_data->id,'start_date'=>Carbon::now(),'free_plan_id'=>1,'see_point'=>0,'increase_point'=>0,'remaining_point'=>$free_plan_data->amount,'end_date'=>Carbon::now()->addMonth(1)]);


        $credentials=$request->only('email','password');
        $token=JWTAuth::attempt($credentials);//generate token for this use
        return response()->json(['success' => true, 'data'=>$token,'error'=>'nothing' ]);
    }
}
