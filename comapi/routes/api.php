<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register','RegisterforcomuserapiController@register');
Route::post('/dashboard','EntroapiController@index');
Route::post('/com_first_reg','EntroapiController@company_register_form_first');
Route::post('/com_second_reg','EntroapiController@company_register_form_second');
Route::post('/com_third_reg','EntroapiController@company_register_form_third');
Route::get('/com_detail','EntroapiController@company_detail');
Route::post('/company_edit','EntroapiController@company_edit');
Route::get('/construct_projects','ConstructprojectController@get_projects');
Route::get('/construct_send_mail/{pid}','ConstructprojectController@send_message_form'); //pid is post id
Route::post('construct_send_mail','ConstructprojectController@send_message');
Route::get('construct_mail_view/{mid}','ConstructprojectController@construct_message_view');//mid is massage's id
Route::get('see_tenders','TendersFrontController@see_tenders');//mid is massage's id
Route::get('show_plans','PlanfrontController@show_plans');
//project's detail


Route::get('construct_project_detail','ConstructprojectController@construct_project_detail_one');//project id
Route::get('see_tenders', 'TendersFrontController@see_tenders');




//entro profile
Route::post('profile_detail', 'ProfileController@index');
Route::post('profile_detail/{id}', 'ProfileController@update');
Route::post('changepassword/{id}', 'ChangepasswordController@change');
//end entro profile





Route::get('portfolio/add', 'PortfolioController@add');
Route::post('portfolio/add', 'PortfolioController@add_data');
Route::get('portfolio/list', 'PortfolioController@portfolio');
Route::get('portfolio/delete', 'PortfolioController@delete');
Route::get('portfolio/edit/{id}', 'PortfolioController@edit_form');
Route::post('portfolio/edit/{id}', 'PortfolioController@edit');




Route::get('check_server',function(){
    if($_GET['version'] == 1){
        return response()->json(['status'=>true,'title'=>'Allow','link'=>'','message'=>'','positive_text'=>'','negative_text'=>'']);

    }
    else{
        return response()->json(['status'=>false,'title'=>'Need To Download New Version','link'=>'https://play.google.com/store/apps/details?id=com.facebook.katana','message'=>'New features','positive_text'=>'Download','negative_text'=>'Cancle']);

    }

});

Route::get('token_store','FirebaseController@store_token');









