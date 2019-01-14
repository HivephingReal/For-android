<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectsModel extends Model
{
    //
    protected $table='project';
    protected $fillable=['name','description','competitors','competitor_count','mark','expire_date','business_hub_id','user_id','attachment1','attachment2','publish'];
    protected $dateFormat = 'Y-m-d H:i:s';


    }
