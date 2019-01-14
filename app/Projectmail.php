<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projectmail extends Model
{
    //
    protected $table='mail_for_project';
    protected $fillable=['from_user','to_user','title','subject','status','project_id','soft_del'];


    public function ScopeWithinmonth($query,$month,$year)
    {
        return $query->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->get();

    }
}
