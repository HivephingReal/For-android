<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreePlan extends Model
{
    //
    protected $table='free_plan';
    public $fillable=['amount','increase'];
}
