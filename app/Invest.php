<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invest extends Model
{
    //
    protected $table='investor';
    protected $fillable=['name','user_id','budget_min','budget_max','background_info','address','city_id','country_id_to_invest','stage_of_product','country_id','business_hub_id','phone'];
}
