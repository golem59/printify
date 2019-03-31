<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderItem extends Model
{
    protected $fillable = ['product_id', 'order_id', 'quantity'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * calculate total price for given order
     * @param int $orderId
     * @return mixed
     */
    public static function totalPriceForOrder(int $orderId)
    {
        return self::query()
            ->leftJoin('Products as p', 'p.id','=','product_id')
            ->where('order_id',$orderId)
            ->sum(DB::raw('quantity * price'));
    }
}
