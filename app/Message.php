<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $table = 'mail';
    protected $fillable = ['title', 'subject', 'to_type', 'from_type', 'from_user', 'to_user', 'soft_del', 'status', 'type', 'project_id'];
    protected $dateFormat = 'Y-m-d H:i:s';


}
