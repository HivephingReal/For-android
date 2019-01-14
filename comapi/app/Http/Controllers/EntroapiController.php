<?php

namespace App\Http\Controllers;

use App\BplanMail;
use App\Company;
use App\Message;
use App\Projectmail;
use App\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class EntroapiController extends Controller
{
    //
    //
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => 'copy_photo_from_api']);
//        $this->middleware('block');

        // $this->middleware('type', ['except' => ['other_company_detail','copy_photo_from_api']]);
        $this->middleware('NeedToRegister', ['except' => ['company_register_form_first', 'copy_photo_from_api', 'company_register_form_second', 'company_register_form_third']]);
        // $this->middleware('Plans',['except'=>['company_detail','index','company_register_form','company_register_form_two','copy_photo_from_api','company_register_form_three','company_register','company_edit_form','company_edit']]);
    }

    public function index()
    {

        /** @var this is for need to register your company if not u cannot see other tags */
        /** @var end need to register */
        $bmessage = BplanMail::where([['to_user', '=', JWTAuth::user()->id], ['status', '=', 'unread'], ['soft_del', '=', 'no']])->orWhere([['from_user', '=', JWTAuth::user()->id], ['status', '=', 'unread'], ['soft_del', '=', 'no']])->orderBy('created_at', 'desc')->get();
        $blast = BplanMail::where([['to_user', '=', JWTAuth::user()->id], ['status', '=', 'unread'], ['soft_del', '=', 'no']])->orWhere([['from_user', '=', JWTAuth::user()->id], ['status', '=', 'unread'], ['soft_del', '=', 'no']])->orderBy('created_at', 'desc')->first();

        $pmessage = Projectmail::where([['to_user', '=', JWTAuth::user()->id], ['status', '=', 'unread'], ['soft_del', '=', 'no']])->orWhere([['from_user', '=', JWTAuth::user()->id], ['status', '=', 'unread'], ['soft_del', '=', 'no']])->get();
        $plast = Projectmail::where([['to_user', '=', JWTAuth::user()->id], ['status', '=', 'unread'], ['soft_del', '=', 'no']])->orWhere([['from_user', '=', JWTAuth::user()->id], ['status', '=', 'unread'], ['soft_del', '=', 'no']])->orderBy('created_at', 'desc')->first();


        $get_data = Company::where('user_id', JWTAuth::user()->id)->first();
        if (!empty($get_data)) {
            $bid = $get_data->business_hub;
        } else {
            $bid = '';
        }

        $get_business_group = DB::table('business_hub')->where('id', $bid)->first();
        $imp_events = DB::table('tender')
            ->where('business_hub_id', $get_business_group->business_group_id)
            ->orderBy('created_at', 'desc')->limit(20)->get();
        $events = DB::table('tender')
            ->whereMonth('created_at', '!=', '')
            ->orderBy('created_at', 'desc')
            ->limit(20)->get();

        return response()->json(['tenders' => $events, 'userid' => JWTAuth::user()->id,
            'pmessage' => $pmessage, 'last' => $plast,
            'bmessage' => $bmessage, 'blast' => $blast]);

    }


    public function company_register_form()
    {
        $company_count = Company::where('user_id', JWTAuth::user()->id)->count();
        if ($company_count == 0) {
            return response()->json(['note' => 'first form']);
        }
        $company_data = Company::where('user_id', JWTAuth::user()->id)->first();
        if ($company_data->status == 1) {

            return response()->json(['note' => 'second form']);
        } elseif ($company_data->status == 2) {
            return response()->json(['note' => 'third form']);

        } else {
            if ($company_data->status == 3) {
                return redirect('company_detail/' . JWTAuth::user()->id);
                return response()->json(['note' => 'to company detail /' . JWTAuth::user()->id]);

            } else {
                return response()->json(['note' => 'first form']);
            }
        }
    }

//first com reg form

    public function company_register_form_first(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|min:2|max:120',
                'email' => 'required|email|unique:company,email',
                'business_hub' => 'required|min:1|max:20',
                'country_id' => 'required|numeric|min:1',
                'city_id' => 'required|min:1',
                'phone' => 'required|numeric|digits_between:5,40',
                'address' => 'required|min:15|max:600'
            ]);

        if ($validator->fails()) {
            return response()->json(['note' => $validator->errors()->first()]);
        }

        $input = $request->all();
        $input['user_id'] = JWTAuth::user()->id;
        $input['website'] = '';
        $input['facebook'] = '';
        $input['investment'] = '';
        $input['logo'] = '';
        $input['registration_status'] = '';
        $input['year_esta'] = '';
        $input['description'] = '';
        $input['ceo_name'] = '';
        $input['ceo_email'] = '';
        $input['status'] = 1;

        if (Company::create($input)) {
            return response()->json(['note' => 2]);
        } else {
            return response()->json(['note' => 'connection error']);
        }
    }

    //end first com reg form\


    //second first reg form

    public function company_register_form_second(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'website' => 'max:127',
                'facebook' => 'max:127',
                'investment' => 'required|numeric|min:1|max:2',
                'registration_status' => 'required|numeric|min:1|max:2',
                'description' => 'required|min:12|max:3330',
//                'logo' => 'mimes:jpeg,bmp,png,jpg,gif'
            ]);

        if ($validator->fails()) {
            return response()->json(['note' => $validator->errors()->first()]);
        }

        if (!empty($request->file('logo'))) {
            $request->file('logo')->move(base_path() . '/public/users/entro/photo', Carbon::now()->timestamp . $request->file('logo')->getClientOriginalName());

            $logoname = Carbon::now()->timestamp . $request->file('logo')->getClientOriginalName();
            $input = $request->except('_token');
            $input['logo'] = $logoname;

            Company::where('user_id', JWTAuth::user()->id)
                ->update(
                    [
                        'status' => 2,
                        'year_esta' => $request->year_esta,
                        'logo' => $logoname,
                        'website' => $request->website,
                        'facebook' => $request->facebook,
                        'investment' => $request->investment,
                        'registration_status' => $request->registration_status,
                        'year_esta' => $request->year_esta,
                        'description' => $request->description
                    ]);
        } else {
            $input = $request->except('_token');

            Company::where('user_id', JWTAuth::user()->id)
                ->update(
                    [
                        'status' => 2,
                        'year_esta' => $request->year_esta,
                        'website' => $request->website,
                        'facebook' => $request->facebook,
                        'investment' => $request->investment,
                        'registration_status' => $request->registration_status,
                        'year_esta' => $request->year_esta,
                        'description' => $request->description
                    ]);
        }

        Message::create(
            [
                'from_user' => 34,
                'to_user' => JWTAuth::user()->id,
                'title' => 'Welcome From Hivephing',
                'subject' => 'Welcome From Hivephing',
                'from_type' => 'c',
                'to_type' => 'c',
                'status' => 'unread',
                'project_id' => '',
                'type' => '', 'soft_del' => 'no'
            ]);

        return response()->json(['note' => 3]);

    }

    //end second com reg form

    public function company_register_form_third(Request $request)
    {
        $company_count = Company::where('user_id', JWTAuth::user()->id)->count();

        $validator = Validator::make($request->all(), ['ceo_name' => 'min:5|max:130', 'ceo_email' => 'email|unique:company,ceo_email']);
        if ($validator->fails()) {
            return response()->json(['note' => $validator->errors()->first()]);
        } else {
            Company::where('user_id', JWTAuth::user()->id)->update(['ceo_name' => $request->ceo_name, 'status' => 3, 'ceo_email' => $request->ceo_email]);
            return response()->json(['note' => 4]);

        }
    }


    public function company_detail()
    {
        $dd = Company::where('user_id', JWTAuth::user()->id)->first();
        $rate = Rating::where('com_id', '=', $dd->id)->get()->count();
            $c_name = DB::table('cities')->where('id',$dd->city_id);
            if($c_name->count() != 0) {
                $cname = $c_name->first()->name;
            }else{
                $cname='';
            }
            $dd->city_name=$cname;
            $bname=DB::table('business_hub')->where('id',$dd->business_hub);
            if($bname->count() != 0) {
                $bname = $bname->first()->description;
            }else{
                $bname='';
            }
            $dd->business_type=$bname;
            $dd->expire_date='f';


        if ($dd->count() > 0) {
            return response()->json(['success'=>true,'com_data' => $dd]);
        }
    }


    public function company_edit(Request $request)
    {
        $old_data = Company::where('id', $request->id)->first();
        $validator = Validator::make($request->all(), ['name' => 'required|min:2|max:120', 'business_hub' => 'required|min:1|max:20', 'country_id' => 'required|numeric|min:1',
            'city_id' => 'required|numeric', 'logo' => 'mimes:jpeg,bmp,png,jpg,gif', 'address' => 'required|max:1000',
            'email' => 'required|email|unique:company,email,' . $request->id, 'phone' => 'required|numeric|digits_between:5,14',
            'website' => 'max:27',
            'facebook' => 'max:27',
            'investment' => 'required|numeric|min:1|max:2',
            'registration_status' => 'required|numeric|min:1|max:2',
            'description' => 'required|min:12|max:3330',
            'ceo_name' => 'min:5|max:130', 'ceo_email' => 'email|unique:company,ceo_email,' . $request->id]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        } else {
            if (!empty($request->file('logo'))) {
                if (unlink($_SERVER['DOCUMENT_ROOT'] . '/companies/public/users/entro/photo/' . $old_data->logo)) {

                } else {
                    return 'Cannot Delete';
                }
                $request->file('logo')->move(base_path() . '/public/users/entro/photo', Carbon::now()->timestamp . $request->file('logo')->getClientOriginalName());
                $data_photo = Carbon::now()->timestamp . $request->file('logo')->getClientOriginalName();
            } else {
                $data_photo = $old_data->logo;
            }
            $input = $request->except('_token');
            $input['logo'] = $data_photo;
            $input['user_id'] = Auth::user()->id;
            $input['status'] = 3;
            if (Company::where('id', $request->id)->update($input)) {
                return response()->json(['note' => 'success return to company detail']);
            } else {
                return response()->json(['note' => 'Try again']);
            }
        }
    }


}
