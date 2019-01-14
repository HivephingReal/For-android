<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    public function test(){
        return 'ff';
    }
}
