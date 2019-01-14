<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Construct extends Model
{
    protected $table = 'construct';

    protected $fillable = ['name'];

    protected $dateFormat = 'Y-m-d H:i:s';
}
