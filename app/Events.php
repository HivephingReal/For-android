<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    //
    protected $table='events';
    protected $fillable=['title','description','start_date','end_date','business_hub_id','photo'];
    protected $dateFormat = 'Y-m-d H:i:s';

}
