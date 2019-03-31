<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected $hidden = ['created_at', 'updated_at'];
}
