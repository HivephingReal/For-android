<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Freeplanforuser extends Model
{
    //
    protected $table='user_get_free_plan';
    public $fillable=['user_id','start_date','see_point','free_plan_id','increase_point','end_date','remaining_point'];
}
