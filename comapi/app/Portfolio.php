<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    //
    public $table='portfolio';
    protected $fillable = ['com_id', 'user_id', 'photo','project_name','address','description','start_date','end_date','create_at','update_at'];

}
