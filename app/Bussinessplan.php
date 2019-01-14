<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bussinessplan extends Model
{
    //
    protected $fillable=['visitor_id','city_id','like_count','company_id','title','description','business_hub_id','country_id','business_group_id','stage_of_product','target_customer','market_value','competitor_des','user_position','founders','count_employees','background_employees','looking_investment','sharer','monthly_revenues','monthly_expenses','background_founders','visitor_id','video_url','visitor_count','status'];

    protected $table='business_plan';
    protected $dateFormat = 'Y-m-d H:i:s';

}
