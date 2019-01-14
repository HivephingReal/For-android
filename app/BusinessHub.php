<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessHub extends Model
{
    protected $table="business_hub";

    protected $fillable = ['description','business_group_id'];
    protected $dateFormat = 'Y-m-d H:i:s';
}
