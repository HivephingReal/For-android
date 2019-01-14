<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssoRequest extends Model
{
    //
    protected $table='request_tojoin';
    protected $fillable=['com_id','user_id','request_message','confirm','asso_user','asso_id'];

}
