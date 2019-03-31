<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['info', 'country_code'];

    protected $hidden = ['created_at', 'updated_at'];
}
