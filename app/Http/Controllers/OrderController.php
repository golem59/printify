<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Http\Requests\OrderRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    const MAX_API_CALLING_BY_COUNTRY = 3;
    const ORDER_IS_EMPTY = 'order is empty!';
    const TOO_MANY_ATTEMPTS = 'too many attempts from your country!';
    const ERROR_TOTAL_PRICE_IS_LESS_THAN_MINIMUM = 'total price is less than minimum, order was deactivated!';
    const MIN_PRICE = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Order::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        $countryCode = $request->get('country_code');
        $products = $request->get('products');
        if(!count($products)){
            return response()->json(['error' => self::ORDER_IS_EMPTY]);
        }
        $info = json_encode($products);

        $products = $this->sumProductsQuantity($products);

        if(!$this->isThrottlingOk($countryCode)){
            return response()->json(['error' => self::TOO_MANY_ATTEMPTS]);
        }

        $order = $this->createOrder($countryCode,$info);
        $this->createOrderItems($products, $order->id);
        $sum = OrderItem::totalPriceForOrder($order->id);

        if($sum < self::MIN_PRICE){
            $order->is_active = false;
            $order->save();
            return response()->json(['error' => self::ERROR_TOTAL_PRICE_IS_LESS_THAN_MINIMUM]);
        }
        return response()->json(['total_price' => $sum]);
    }

    /**
     * sum quantity for same products
     * @param array $products
     * @return array
     */
    protected function sumProductsQuantity(array $products)
    {
        $preparedProducts = [];
        foreach ($products as $product){
            $preparedProducts[$product['id']] =  $preparedProducts[$product['id']]??0;
            $preparedProducts[$product['id']] += $product['quantity'];
        }

        return $preparedProducts;
    }

    /**
     * @param array $products
     * @param int $orderId
     */
    protected function createOrderItems(array $products, int $orderId)
    {
        foreach ($products as $key => $value){
            OrderItem::query()->create([
                'order_id' => $orderId,
                'product_id' => $key,
                'quantity' => $value
            ]);
        }
    }

    /**
     * @param string $countryCode
     * @param string $info
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    protected function createOrder(string $countryCode, string $info)
    {
        $orderArray = [
            'country_code' => $countryCode,
            'info' => $info
        ];

        return Order::query()->create($orderArray);
    }

    /**
     * @param $country
     * @return bool
     */
    protected function isThrottlingOk($country)
    {
        $counter = Cache::get($country, 0);
        $counter++;
        if($counter > self::MAX_API_CALLING_BY_COUNTRY){
            return false;
        }

        $expiresAt = Carbon::now()->addSeconds(1);
        Cache::store('file')->put($country, $counter, $expiresAt);
        return true;
    }
}
