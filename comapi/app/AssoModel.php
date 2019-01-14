<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssoModel extends Model
{
    //
    protected $table='association';
    protected $fillable=['name','business_hub','country','city','address','phone','description','web','facebook','members_id','user_id'];
}
