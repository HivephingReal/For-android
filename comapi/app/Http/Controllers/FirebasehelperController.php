<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FirebaseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class FirebasehelperController extends Controller
{
    //
    public $body;
    public $title;
    public $token;

    public function __construct()
    {

    }

    public function sendnotimsg($body,$title,$token)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();



        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
        if($downstreamResponse){

        }


    }

}
