<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acitivity extends Model
{
    //
    protected $table='activities';
    protected  $fillable=['user_id','description','image'];
        protected $dateFormat = 'Y-m-d H:i:s';

}
