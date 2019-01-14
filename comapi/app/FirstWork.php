<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirstWork extends Model
{
    //
    protected $table='first_work_type';
    protected $fillable=['name'];
    protected $connection='mysql_service';
}
