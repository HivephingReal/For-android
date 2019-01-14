<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $table = 'design';

    protected $fillable = ['name'];

    protected $dateFormat = 'Y-m-d H:i:s';
}
