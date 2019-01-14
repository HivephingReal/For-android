<?php

namespace App\Http\Controllers;

use App\AssoModel;
use App\Company;
use App\Invest;
use App\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class TendersFrontController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('jwt.auth');
//        $this->middleware('block');
////        $this->middleware('type', ['only' => ['see_tenders']]);
////        $this->middleware('asso_type', ['only' => ['asso_see_tenders']]);
//        $this->middleware('NeedToRegister', ['only' => ['see_tenders']]);
//        $this->middleware('Plans');

    }
    public function see_tenders(){

            $get_data = Company::where('user_id', JWTAuth::user()->id)->first();
            if(!empty($get_data)) {
                $bid = $get_data->business_hub;
            }else{
                $bid='';
            }



        $get_business_group=DB::table('business_hub')->where('id',$bid)->first();
        $imp_events =Tender::where('business_hub_id',$get_business_group->business_group_id)->orderBy('created_at', 'desc')->get();
        $events = Tender::whereMonth('created_at', '!=', '')->orderBy('created_at', 'desc')->get();
        $data=[];
        foreach($events as $e){
            $e->description=strip_tags($e->description);

            $data[]=$e;

        }
        return response()->json(['success'=>true,'data' => $events]);
    }
    public function see_tenders_detail($id){
        $data=Tender::where('id',$id)->first();
        return view('user.entra.readtender', ['data'=>$data]);

    }
    public function asso_see_tenders(){
        if (Auth::user()->type == 3) {
            $get_data = AssoModel::where('user_id', Auth::user()->id)->first();
            if(!empty($get_data)) {
                $bid = $get_data->business_hub;
            }else{
                $bid='';
            }
        }
        $get_business_group=DB::table('business_hub')->where('id',$bid)->first();
        $imp_events =Tender::where('business_hub_id',$get_business_group->business_group_id)->orderBy('created_at', 'desc')->limit(20)->get();
        $events = Tender::whereMonth('created_at', '!=', '')->orderBy('created_at', 'desc')->limit(20)->get();
        return view('tenders.asso_front', ['events' => $events,'imp_events'=>$imp_events,'bid'=>$bid]);
    }
}
