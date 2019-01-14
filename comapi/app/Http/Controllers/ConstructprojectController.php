<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class ConstructprojectController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['detail_without_auth']]);
        $this->middleware('block', ['except' => ['detail_without_auth']]);

        $this->middleware('NeedToRegister', ['except' => ['detail_without_auth']]);

    }
    public function sendquotation(){
        return view('user.entra.send_quotation');
    }

    public function get_projects()
    {
        $con_com_data = DB::table('company')->where('user_id', JWTAuth::user()->id)->first();

        switch ($con_com_data->business_hub) {
            case 1 :
                $fr = ['fr4','rb4'];
                break;
            case 2:
                $fr = ['fr3','rb3'];
                break;
            case 3:
                $fr = ['fr5','fr5'];
                break;
            case 4:
                $fr = ['fr1','rb2'];
                break;

            case 5:
                $fr = ['fr7','fr7'];
                break;

            case 6:
                $fr = ['fr8','rb5'];
                break;

            case 7:
                $fr = ['fn1','fn2','fn3','fn4'];
                break;

            case 8:
                $fr = ['fr6','fn2','fn3','fn4'];
                break;
            case 9:
                $fr = ['rb1','b1','b2','b3'];
                break;
            case 10:
                $fr = ['fr8','rb5'];
                break;

        }

//        $data = DB::connection('mysql_service')->table('for_repair')->where([['city', '=', $con_com_data->city_id], ['close', 0], ['confirm', '=', 'confirmed']])->whereIn('fr_type',$fr);
        // under function is temp function
        $data = DB::connection('mysql_service')->table('for_repair')->where([['city', '=', $con_com_data->city_id],['confirm', '=', 'confirmed']])->whereIn('fr_type',$fr)->orderBy('id','desc');

//        $limit_q=DB::table('user_saw_this_plan')->where('project_id',$data->id)->count();
//        $user_saw_this=DB::table('user_saw_this_plan')->where([['project_id','=',$data->id],['user_id',JWTAuth::user()->id]])->count();
//        $user_saw_this=DB::table('user_saw_this_plan')->where([['project_id','=',$data->id],['user_id',JWTAuth::user()->id]])->count();


        $darray=[];

        foreach($data->get() as $d)
        {
            $comp = DB::table('user_saw_this_plan')->where([['project_id', '=', $d->id]])->count();
            $req_p = DB::connection('mysql_service')->table('request')->where([['post_id', '=', $d->id], ['requester_id', '=', JWTAuth::user()->id]]);
            $c_name = DB::table('cities')->where('id',$d->city)->first()->name;


            $limit_q = DB::table('user_saw_this_plan')->where('project_id', $d->id)->count();
//
//            if($limit_q <= $d->quotation)
//            {
//                $user_saw_this = DB::table('user_saw_this_plan')->where([['project_id','=',$d->id],['user_id',JWTAuth::user()->id]])->count();
//
//                if($user_saw_this > 0)
//                {
//                    $darray[]=$d;
//
//                }
//                else
//                {
//                    continue;
//                }
//            }
            if($req_p->count() > 0){
                if($req_p->first()->status == 'con') {
                    $d->state = 'View Detail';
                }else{
                    $d->state='Requested';
                }

            }
            else{
                $d->state='Request Project';
            }
            $d->city=$c_name;
            $d->comp_count=$comp;
            $d->description=strip_tags($d->description);
//            else
//            {
            $darray[] = $d;
//            }

        }
        $dc=collect($darray);


        return response()->json(['success'=>true,'data'=>$dc,'error'=>'nothing','user_data'=>JWTAuth::user()]);

//        dd($point);
    }



    public function construct_project_detail_one()
    {
        //this is real detail function
        $id=$_GET['project_id'];
        $data = DB::connection('mysql_service')->table('for_repair')->where('id', $id)->first();
        $check = DB::connection('mysql_service')->table('request')->where([['post_id','=',$data->id],['post_uploader_id','=',$data->user_id],['requester_id','=',JWTAuth::user()->id]])->count();
        if($check == 0){
            DB::connection('mysql_service')->table('request')->insert(['post_id'=>$id,'post_uploader_id'=>$data->user_id,'status'=>'rq','requester_id'=>JWTAuth::user()->id,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        }else{
            $to_check= DB::connection('mysql_service')->table('request')->where([['post_id','=',$data->id],['post_uploader_id','=',$data->user_id],['requester_id','=',JWTAuth::user()->id]])->first();
            if($to_check->status == 'con'){
                $com_data = DB::table('company')->where('user_id', '=', JWTAuth::user()->id)->first();
                $message_data = DB::connection('mysql_service')->table('message')->where([['post_id', '=', $id], ['com_id', '=', $com_data->id]])->orderBy('created_at', 'desc')->get();
                return response()->json(['data'=>$data,'message_data'=>$message_data,'note'=>'Dnt show ph and address']); //

            }

        }
        $req_p = DB::connection('mysql_service')->table('request')->where([['post_id', '=', $id], ['requester_id', '=', JWTAuth::user()->id]]);


//
//            if($limit_q <= $d->quotation)
//            {
//                $user_saw_this = DB::table('user_saw_this_plan')->where([['project_id','=',$d->id],['user_id',JWTAuth::user()->id]])->count();
//
//                if($user_saw_this > 0)
//                {
//                    $darray[]=$d;
//
//                }
//                else
//                {
//                    continue;
//                }
//            }
        if($req_p->count() > 0){
            if($req_p->first()->status == 'con') {
                $data->state = 'View Detail';
            }else{
                $data->state='Requested';
            }

        }
        else{
            $data->state='Request Project';
        }
        $c_name = DB::table('cities')->where('id',$id)->first()->name;
        $data->city=$c_name;

        $com_data = DB::table('company')->where('user_id', '=', JWTAuth::user()->id)->first();
        $message_data = DB::connection('mysql_service')->table('message')->where([['post_id', '=', $id], ['com_id', '=', $com_data->id]])->orderBy('created_at', 'desc')->get();

//      return view('user.entra.construct_project_detail_one', ['data' => $data, 'message_data' => $message_data]);



        $check_rq=DB::connection('mysql_service')->table('request')->where([['post_id','=',$data->id],['post_uploader_id','=',$data->user_id],['requester_id','=',JWTAuth::user()->id]])->first();

        return response()->json(['success'=>true,'data'=>$data,'note'=>'show ph and address']);
    }



    public function detail_without_auth($id){
        Session::put('no_auth','yes');
        return redirect('entra/construct_project_detail/'.$id);
    }

    public function upload_quotation(Request $request)
    {
        if (!empty($request->file('quotation_file')))
        {
            $request->file('quotation_file')->move(base_path() . '/public/users/entro/qfile/', Carbon::now()->timestamp . $request->file('quotation_file')->getClientOriginalName());
            $qfname = Carbon::now()->timestamp . $request->file('quotation_file')->getClientOriginalName();

        } else {
            $qfname = '';

        }
        DB::connection('mysql_service')->table('upload_form_for_quo')->insert(['project_id'=>$request->pid,'com_id'=>$request->cid,'file'=>$qfname,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);

        return redirect()->back();
    }
    public function detail($id)
    {
        $d = DB::connection('mysql_service')->table('for_repair')->where('id', $id)->first();

        $limit_q = DB::table('user_saw_this_plan')->where('project_id', $id)->count();

        if($limit_q >= $d->quotation){
            Session::flash('ex','Expired');

            return redirect('entra/construct_projects');
        }
        //to check user already see this project
        $castp = DB::table('see_projects_with_plan')->where([['user_id', '=', JWTAuth::user()->id], ['project_id', '=', $id]])->count();
        if ($castp == 0)
        {
            //for free plan
            $limit = DB::table('user_get_free_plan')->where('user_id', '=', JWTAuth::user()->id);
            //take user's free plan
            $get_define_point = DB::connection('mysql_service')->table('for_repair')->where('id', $id)->first();
            //get projects define point
            $com = DB::table('company')->where('user_id', JWTAuth::user()->id)->first()->id;
            //get user's company id
            $com_plan = DB::table('company_with_plan')->where('com_id', $com);
            //get company's plan


            if($limit->count() > 0)
                //if user's have free plan
            {
                $free_plan_data = DB::table('free_plan')->where('id', $limit->first()->free_plan_id)->first();
                //get free plan data

                if ($limit->count() != 0)
                    //if user's free plan is not expired
                {
                    //for free plan
                    //check his remaining point and bonus point is greater than project define point
                    if (($limit->first()->remaining_point + $limit->first()->increase_point) >= $get_define_point->project_define_point)
                    {
                        // if his remaining point is enough for project define point
                        if (($limit->first()->remaining_point - $get_define_point->project_define_point) > 0)
                        {
                            //get point from his remaining point
                            $new_rem_point = ($limit->first()->remaining_point - $get_define_point->project_define_point);
                            //put bonus point for him
                            $new_bonus_point = $limit->first()->increase_point+$free_plan_data->increase;
                        }
                        else
                        {
                            //if his remaining point is equal to 0
                            if ($limit->first()->remaining_point == 0)
                            {
                                //get point from his bonus point
                                $new_bonus_point = ($limit->first()->increase_point - $get_define_point->project_define_point);
                                $new_rem_point = 0;
                                $new_bonus_point = $new_bonus_point;
                            }
                            else
                            {
                                //if his remaining point is less than project define point
                                if ($limit->first()->remaining_point < $get_define_point->project_define_point)
                                {
                                    $half_reduce_point = $get_define_point->project_define_point - $limit->first()->remaining_point;
                                    $aku_point = $limit->first()->increase_point - $half_reduce_point;
                                    $new_rem_point = 0;
                                    $new_bonus_point = $aku_point;
                                }
                                else
                                {
                                    $new_rem_point = $limit->first()->remaining_point - $get_define_point->project_define_point;
                                    $new_bonus_point = $limit->first()->increase_point;

                                }

                            }

                        }

                        $new_see_point = $limit->first()->see_point + 1;
                        DB::table('user_get_free_plan')->where('user_id', '=', JWTAuth::user()->id)->update(['remaining_point' => $new_rem_point, 'see_point' => $new_see_point, 'increase_point' => $new_bonus_point]);
                    }
                    elseif($com_plan->count() > 0)
                    {
                        if($com_plan->count() > 1)
                        {
                            $com_rm_m = 0;

                            foreach($com_plan->get() as $p)
                            {
                                $com_rm_m += $p->remaining_point;
                            }
                            //                     dd($com_rm_m);
                        }
                        else
                        {
                            $com_rm = $com_plan->first()->remaining_point;
                            //                    dd($com_rm);

                        }


                        if (isset($com_rm) && ($com_rm >= $get_define_point->project_define_point))
                        {
                            $sub = $com_rm - $get_define_point->project_define_point;
//                     dd($sub_m);

                            DB::table('company_with_plan')->where('com_id', $com)->update(['remaining_point' => $sub]);
                            DB::table('see_projects_with_plan')->insert(['user_id' => JWTAuth::user()->id, 'plan' => 'plan', 'project_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
                            DB::table('user_saw_this_plan')->insert(['user_id' => JWTAuth::user()->id, 'project_id'=>$id,'created_at'=>Carbon::now()]);
                        }
                        elseif ($com_rm_m >= $get_define_point->project_define_point)
                        {
                            $sub_m = round(($com_rm_m - $get_define_point->project_define_point)/2);
//                    dd($sub_m);
                            DB::table('company_with_plan')->where('com_id', $com)->update(['remaining_point' => $sub_m]);
                            DB::table('see_projects_with_plan')->insert(['user_id' => JWTAuth::user()->id, 'plan' => 'plan', 'project_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
                            DB::table('user_saw_this_plan')->insert(['user_id' => JWTAuth::user()->id, 'project_id'=>$id,'created_at'=>Carbon::now()]);
                        }
                        else
                        {
                            Session::flash('expire','yes');
                            return redirect('entra/construct_projects');
                        }
                    }
                    else
                    {
                        Session::flash('not_permit', 'no');
                        return redirect('entra/construct_projects');
                    }
                }
                else
                {
                    //for buying plan

                    Session::flash('expire','yes');
                    return redirect('entra/construct_projects');
                }

                DB::table('see_projects_with_plan')->insert(['user_id' => JWTAuth::user()->id, 'plan' => 'free', 'project_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
                DB::table('user_saw_this_plan')->insert(['user_id' => JWTAuth::user()->id, 'project_id'=>$id,'created_at'=>Carbon::now()]);

            }
            elseif($com_plan->count() > 0)
            {
                if($com_plan->count() > 1)
                {
                    $com_rm_m = 0;

                    foreach($com_plan->get() as $p)
                    {
                        $com_rm_m += $p->remaining_point;
                    }
//                    dd($com_rm_m);
                }
                else
                {
                    $com_rm = $com_plan->first()->remaining_point;
//                    dd($com_rm);

                }


                if (isset($com_rm) && $com_rm >= $get_define_point->project_define_point)
                {
                    $sub = $com_rm - $get_define_point->project_define_point;
//                     dd($sub_m);

                    DB::table('company_with_plan')->where('com_id', $com)->update(['remaining_point' => $sub]);
                    DB::table('see_projects_with_plan')->insert(['user_id' => JWTAuth::user()->id, 'plan' => 'plan', 'project_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
                    DB::table('user_saw_this_plan')->insert(['user_id' => JWTAuth::user()->id, 'project_id'=>$id,'created_at'=>Carbon::now()]);
                }
                elseif ($com_rm_m >= $get_define_point->project_define_point)
                {
                    $sub_m = round(($com_rm_m - $get_define_point->project_define_point)/2);
//                    dd($sub_m);
                    DB::table('company_with_plan')->where('com_id', $com)->update(['remaining_point' => $sub_m]);
                    DB::table('see_projects_with_plan')->insert(['user_id' => JWTAuth::user()->id, 'plan' => 'plan', 'project_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
                    DB::table('user_saw_this_plan')->insert(['user_id' => JWTAuth::user()->id, 'project_id'=>$id,'created_at'=>Carbon::now()]);
                }
                else
                {
                    Session::flash('expire','yes');
                    return redirect('entra/construct_projects');
                }
            }
            else
            {
                Session::flash('expire','yes');
                return redirect('entra/construct_projects');
            }


            //end for free plan

        }


        $data = DB::connection('mysql_service')->table('for_repair')->where('id', $id)->first();
        $com_data = DB::table('company')->where('user_id', '=', JWTAuth::user()->id)->first();
        $message_data = DB::connection('mysql_service')->table('message')->where([['post_id', '=', $id], ['com_id', '=', $com_data->id]])->orderBy('created_at', 'desc')->get();
        return view('user.entra.construct_project_detail', ['data' => $data, 'message_data' => $message_data]);
    }

    public function send_message_form($post_id)
    {
        $post_data = DB::connection('mysql_service')->table('for_repair')->where('id', $post_id)->first();
        $user_data = DB::connection('mysql_service')->table('users')->where('id', $post_data->user_id)->first();
        return response()->json(['post_data' => $post_data, 'user_data' => $user_data]);

    }

    public function send_message(Request $request)
    {
        $success = DB::connection('mysql_service')->table('message')->insert(['post_id' => $request->post_id, 'from_user' => 'com', 'user_id' => $request->user_id, 'com_id' => $request->com_id, 'message' => $request->message, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        $com_data = DB::table('company')->where('user_id', '=', JWTAuth::user()->id)->first();
        $pdata = DB::connection('mysql_service')->table('for_repair')->where('id', $request->post_id)->first();
        DB::connection('mysql_service')->table('relation_com_and_user')->insert(['com_id' => $request->com_id, 'post_id' => $request->post_id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        if ($success) {
//            return redirect('entra/construct_project_detail/' . $request->post_id);
            return response()->json(['success'=>'successfully send','post_id'=>$request->post_id,'note'=>'project detail page']);
        }
    }

    public function construct_message_view($mid)
    {
        $mdata = DB::connection('mysql_service')->table('message')->where('id', $mid)->first();
        $post_data = DB::connection('mysql_service')->table('for_repair')->where('id', $mdata->post_id)->first();
        $user_data = DB::connection('mysql_service')->table('users')->where('id', $mdata->user_id)->first();
//        return view('user.entra.mails.construct_mail.mail_view', ['data' => $mdata, 'post_data' => $post_data, 'user_data' => $user_data]);
        return response()->json(['data' => $mdata, 'post_data' => $post_data, 'user_data' => $user_data]);

    }
}
