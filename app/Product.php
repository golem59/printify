<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['price', 'product_type_id', 'color', 'size'];

    protected $hidden = ['created_at', 'updated_at'];
}
