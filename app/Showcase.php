<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Showcase extends Model
{
    protected $table = 'showcase';

    protected  $fillable = ['user_id', 'name', 'photo', 'description'];

    protected  $dateFormat = 'Y-m-d H:i:s';
}
