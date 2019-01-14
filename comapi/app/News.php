<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\NewsCategory;

class News extends Model
{
    protected $table='news';
    protected $fillable = ['title','des','image','NewsCategory_id', 'op_id'];
    protected $dateFormat = 'Y-m-d H:i:s';

}
