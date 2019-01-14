<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    //
    protected $table='plans';
    protected $fillable=['user_id','user_type','plan_type','start_date','expire_date','view_project','create_project','create_business_plan','duration_month'];
    protected $dateFormat = 'Y-m-d H:i:s';

}
