<?php

namespace App\Http\Controllers;

use App\Plans;
use App\RegisterfeeModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PlanfrontController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('jwt.auth');
        $this->middleware('block');

    }

    public function show_plans()
    {
        $plan = DB::table('construct_plan')->get();

        return response()->json(['data'=>$plan]);
    }

    public function buy_plan_form($p)
    {
        return view('user.entra.plan.buy_plan', ['p' => $p]);
    }

    public function buy_plan(Request $request)
    {
        $validation = Validator::make($request->all(), ['p' => 'required', 'duration' => 'required']);
        if ($validation->fails())
        {
            return $validation->errors();
        }

        $already_has = Plans::where('user_id', Auth::user()->id)->count();
        if ($already_has > 0) {
            Session::flash('had_plan', 'You already have a plan');
            return redirect()->back();
        }
        $no_balance = RegisterfeeModel::where('user_id', Auth::user()->id)->count();
        if ($no_balance == 0) {
            Session::flash('had_plan', 'Your banlance is not enough');
            return redirect()->back();
        }

        $but_not_eb = RegisterfeeModel::where('user_id', Auth::user()->id)->first();

        if (Auth::user()->type == 1)
        {
            if ($request->p == 'A')
            {
                $view_p = 20;
                $cr_p = 10;
                $cbp = 3;

                if ($request->duration == 'number:1') {
                    $amount = 50000;
                }
                if ($request->duration == 'number:3') {
                    $amount = 138000;

                }
                if ($request->duration == 'number:6') {
                    $amount = 270000;


                }
                if ($request->duration == 'number:12') {
                    $amount = 528000;


                }

            }

            if ($request->p == 'B') {
                $view_p = 40;
                $cr_p = 20;
                $cbp = 5;
                if ($request->duration == 'number:1') {
                    $amount = 80000;
                }
                if ($request->duration == 'number:3') {
                    $amount = 220800;

                }
                if ($request->duration == 'number:6') {
                    $amount = 432000;


                }
                if ($request->duration == 'number:12') {
                    $amount = 844800;


                }

            }

            if ($request->p == 'C') {
                $view_p = 400000;
                $cr_p = 200000;
                $cbp = 50000;
                if ($request->duration == 'number:1') {
                    $amount = 100000;
                }
                if ($request->duration == 'number:3') {
                    $amount = 270000;

                }
                if ($request->duration == 'number:6') {
                    $amount = 528000;


                }
                if ($request->duration == 'number:12') {
                    $amount = 1020000;


                }


            }
        }
        if (Auth::user()->type == 2) {
            $view_p = 40;
            $cr_p = 20;
            $cbp = 20;
            if ($request->p == 'A') {

                if ($request->duration == 'number:1') {
                    $amount = 80000;
                }
                if ($request->duration == 'number:3') {
                    $amount = 220800;

                }
                if ($request->duration == 'number:6') {
                    $amount = 432000;


                }
                if ($request->duration == 'number:12') {
                    $amount = 844800;


                }

            }

            if ($request->p == 'B') {
                $view_p = 400000;
                $cr_p = 200000;
                $cbp = 200000;
                if ($request->duration == 'number:1') {
                    $amount = 100000;
                }
                if ($request->duration == 'number:3') {
                    $amount = 270000;

                }
                if ($request->duration == 'number:6') {
                    $amount = 528000;
                }
                if ($request->duration == 'number:12') {
                    $amount = 1020000;
                }
            }
        }

        if ( $amount > $but_not_eb->amount) {
            Session::flash('had_plan', 'Your banlance is not enough');
            return redirect()->back();
        } else {
            if ($request->duration == 'number:1') {
                $month = 1;
            }
            if ($request->duration == 'number:3') {
                $month = 3;

            }
            if ($request->duration == 'number:6') {
                $month = 6;
            }
            if ($request->duration == 'number:12') {
                $month = 12;
            }
            if($request->p == 'A'){
                $p=1;
            }
            if($request->p == 'B'){
                $p=2;
            } if($request->p == 'C'){
                $p=3;
            }
            $ex = Carbon::now()->addMonths($month);
            $success = Plans::create([
                'user_id' => Auth::user()->id,
                'view_project' => $view_p, 'duration_month' => $month,
                'create_project' => $cr_p, 'create_business_plan' => $cbp,
                'user_type' => Auth::user()->type, 'plan_type' => $p,
                'start_date' => Carbon::now(), 'expire_date' => $ex
            ]);

            if ($success) {
                $newam = $but_not_eb->amount - $amount;
                RegisterfeeModel::where('user_id', Auth::user()->id)->update(['amount' => $newam]);
                Session::flash('success', 'Successfully for buying a plan');
                return redirect()->back();
                return 'gg';
            } else {
                return 'connection error';
            }
        }


    }
}
