<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Maincat extends Model
{
    protected $table = 'maincategory';

    protected $fillable = ['name'];

    protected $dateFormat = 'Y-m-d H:i:s';
}
