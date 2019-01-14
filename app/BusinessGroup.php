<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessGroup extends Model
{
  protected $table="business_group";

  protected $fillable = ['name'];
}
