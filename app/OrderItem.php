<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['product_id', 'order_id', 'quantity'];

    protected $hidden = ['created_at', 'updated_at'];
}
