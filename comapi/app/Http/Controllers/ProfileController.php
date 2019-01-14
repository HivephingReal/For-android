<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;


class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => 'copy_photo_from_api']);
        $this->middleware('block');

        // $this->middleware('type', ['except' => ['other_company_detail','copy_photo_from_api']]);
        $this->middleware('NeedToRegister', ['except' => ['company_register_form_first', 'copy_photo_from_api', 'company_register_form_second', 'company_register_form_third']]);
        // $this->middleware('Plans',['except'=>['company_detail','index','company_register_form','company_register_form_two','copy_photo_from_api','company_register_form_three','company_register','company_edit_form','company_edit']]);
    }
    public function index()
    {

        if(Auth::user()->type == 2){
            return redirect('inves_profile/profile_detail/'.JWTAuth::user()->id);
        }
        $profile = User::where('id', JWTAuth::user()->id)->first();

        return response()->json(['user_profile'=>$profile]);

    }



    public function inves_index($id)
    {
        if ($id != Auth::user()->id) {
            return "error";
        };
        if(Auth::user()->type == 1){
            return redirect('entra/profile_detail/'.Auth::user()->id);
        }
        $profile = User::where('id', Auth::user()->id)->first();

        return view('profile.inves.profile_detail', ['data' => $profile]);

    }


    public function update($id, Request $request)
    {
        if ($id != JWTAuth::user()->id) {
            return "error";
        };
        $data = User::where('id', $id)->first();
        $data->name = $request->name;
        $data->email = $request->email;

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . JWTAuth::user()->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data->save();

        return response()->json(['status'=>'updated']);
    }



    public function inves_update($id, Request $request)
    {
        if ($id != Auth::user()->id) {
            return "error";
        };
        $data = User::where('id', $id)->first();
        $data->name = $request->name;
        $data->email = $request->email;

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::user()->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data->save();

        return redirect('inves_profile/profile_detail/' . $id)->with('status', 'Your profile is has been updated!');
    }


}