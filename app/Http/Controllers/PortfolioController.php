<?php

namespace App\Http\Controllers;

use App\Portfolio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PortfolioController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => 'copy_photo_from_api']);
//        $this->middleware('block');
//
//        // $this->middleware('type', ['except' => ['other_company_detail','copy_photo_from_api']]);
//        $this->middleware('NeedToRegister', ['except' => ['company_register_form_first', 'copy_photo_from_api', 'company_register_form_second', 'company_register_form_third']]);
        // $this->middleware('Plans',['except'=>['company_detail','index','company_register_form','company_register_form_two','copy_photo_from_api','company_register_form_three','company_register','company_edit_form','company_edit']]);

    }

    public function add(Request $request)
    {
        return view('user.entra.port.add');

    }

    public function add_data(Request $request)
    {


        $validator = Validator::make($request->all(),
            [
                'project_name' => 'required|min:2|max:120',
                'description' => 'required|min:1|max:2220',
//                'start_date' => 'required|date',
//                'end_date' => 'required|date',
//                'photo' => 'required',
                'address' => 'required|min:5|max:1600'
            ]);
        if ($validator->fails()) {
            return response()->json(['note' => $validator->errors()->first()]);
        } else {
//            if (!empty($request->file('photo'))) {
//                $request->file('photo')->move(base_path() . '/public/users/entro/photo/portfolio/', Carbon::now()->timestamp . $request->file('photo')->getClientOriginalName());
//            }
//            $logoname = Carbon::now()->timestamp . $request->file('photo')->getClientOriginalName();
            $input = $request->all();
            $input['user_id'] = JWTAuth::user()->id;
            $com_data = DB::table('company')->where('user_id', JWTAuth::user()->id)->first();
            $input['com_id'] = $com_data->id;
            $input['photo'] = '';
            $data=Portfolio::create($input);
            return response()->json(['note' => 'added','data'=>$data]);
        }
    }

    public function portfolio()
    {
        $get_user_prots = DB::table('portfolio')->where('user_id', JWTAuth::user()->id)->get();
        return response()->json(['data' => $get_user_prots]);
    }

    public function delete()

    {
        $id=$_GET['id'];
        $check_id = DB::table('portfolio')->where('id', $id)->first();
        if ($check_id->user_id == JWTAuth::user()->id) {
//            unlink($_SERVER['DOCUMENT_ROOT'] . '/companies/public/users/entro/photo/portfolio/' . $check_id->photo);

            DB::table('portfolio')->where('id', $id)->delete();
//           Session::flash('activity', 'deleted');
            return response()->json(['note'=>'deleted']);


        } else {
            return response()->json(['note'=>'Cannot Delete']);
        }
    }

    public function edit_form($id)
    {
        $data = Portfolio::where('id', $id)->first();

        return view('user.entra.port.edit_form', ['data' => $data]);
    }

    public function edit(Request $request)
    {
        $old_data = Portfolio::where('id', $request->id)->first();

        $validator = Validator::make($request->all(),
            [
                'project_name' => 'required|min:2|max:120',
                'description' => 'required|min:1|max:2220',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'address' => 'required|min:5|max:1600'
            ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        } else {
            if (!empty($request->file('photo'))) {
//                if (unlink($_SERVER['DOCUMENT_ROOT'] . '/Company/public/users/entro/photo/portfolio/' . $old_data->photo)) {
//
//                } else {
//                    return 'Cannot Delete';
//                }
                $request->file('photo')->move(base_path() . '/public/users/entro/photo/portfolio/', Carbon::now()->timestamp . $request->file('photo')->getClientOriginalName());
                $logoname = Carbon::now()->timestamp . $request->file('photo')->getClientOriginalName();
            } else {
                $logoname = $old_data->photo;
            }
            $input = $request->except('_token');

            $input['user_id'] = Auth::user()->id;
            $input['photo'] = $logoname;
            Portfolio::where('id', $request->id)->update($input);
            Session::flash('activity', 'edited');
            return redirect('entra/portfolio/list');
        }
    }

}
