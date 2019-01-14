<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BplanMail extends Model
{
    //
    protected $table='mail_for_business_plan';
    protected $fillable=['from_user','to_user','title','subject','from_type','to_type','status','business_plan_id','soft_del'];
    protected $dateFormat = 'Y-m-d H:i:s';

    public function ScopeWithinmonth($query,$month,$year)
    {
        return $query->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->get();

    }
}
