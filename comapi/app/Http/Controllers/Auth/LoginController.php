<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login(Request $request)
    {
        $credentials = $request->except('_token');
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=>'We cant find an account with this credentials.']);
        }
        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['success' => false, 'error' => 'We cant find an account with this credentials.']);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to login, please try again.']);
        }
        // all good so return the token

        $company_count = Company::where('user_id', JWTAuth::user()->id)->count();
        if ($company_count == 0) {
            $reg_status='first form';
        }else {
            $company_data = Company::where('user_id', JWTAuth::user()->id)->first();
            if ($company_data->status == 1) {

                $reg_status = 'second form';

            } elseif ($company_data->status == 2) {
//            return response()->json(['note' => 'third form']);
                $user = DB::table('user_block')->where([['user_id', '=', JWTAuth::user()->id], ['circum', '=', 'block']]);

                $no_user=DB::table('user_block')->where([['user_id', '=', JWTAuth::user()->id]]);
                if($no_user->count()== 0){
//                        return response()->json(['note'=>'block']);
                    $reg_status = 'block';


                }else {
                    if ($company_data->count() > 0) {
                        $check_second_data = $company_data->first()->investment;
                        if (empty($check_second_data)) {
                            $comtf = 'false';
                        } else {
                            $comtf = 'true';
                        }
                    } else {
                        $comtf = 'false';
                    }

                    if ($user->count() > 0 and $comtf == 'true') {
                        $reg_status = 'block';
                    }else{
                        $reg_status = 'done';

                    }
                }


            } else {
                if ($company_data->status == 3) {
                    $user = DB::table('user_block')->where([['user_id', '=', JWTAuth::user()->id], ['circum', '=', 'block']]);

                    $no_user=DB::table('user_block')->where([['user_id', '=', JWTAuth::user()->id]]);
                    if($no_user->count()== 0){
//                        return response()->json(['note'=>'block']);
                        $reg_status = 'block';


                    }else {
                        if ($company_data->count() > 0) {
                            $check_second_data = $company_data->first()->investment;
                            if (empty($check_second_data)) {
                                $comtf = 'false';
                            } else {
                                $comtf = 'true';
                            }
                        } else {
                            $comtf = 'false';
                        }

                        if ($user->count() > 0 and $comtf == 'true') {
                            $reg_status = 'block';
                        }else{
                            $reg_status = 'done';

                        }
                    }


                } else {
                    $reg_status = 'first form';

                }
            }
        }


        return response()->json(['success' => true, 'name'=> JWTAuth::user()->name,'email'=>JWTAuth::user()->email,'error' =>'','token' => $token,'note'=>'redirect to dashboard','reg_status'=>$reg_status]);
    }
}
