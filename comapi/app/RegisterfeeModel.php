<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterfeeModel extends Model
{
    //
    protected $table='registration_fee';
    protected $fillable=['user_id','start_date','expire_date','amount'];
}
