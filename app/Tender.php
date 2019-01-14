<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    //
    protected $table='tender';
    protected $fillable=['title','description','photo','business_hub_id', 'op_id','td_point'];
    protected $dateFormat = 'Y-m-d H:i:s';

}
