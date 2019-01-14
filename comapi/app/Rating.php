<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = "rating";

    protected $fillable = ['rate', 'from_user', 'com_id'];

    protected $dateFormat = 'Y-m-d H:i:s';
}
