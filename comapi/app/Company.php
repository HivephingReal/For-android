<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $table='company';
    protected $fillable=['email','year_esta','user_id','name','status','business_hub','country_id','city_id','address','phone','website','facebook','investment','registration_status','expire_date','logo','description','ceo_name','ceo_email'];
    protected $dateFormat = 'Y-m-d H:i:s';


}
