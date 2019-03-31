<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Http\Requests\OrderRequest;
use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    const MAX_API_CALLING_BY_COUNTRY = 3;
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
            return response()->json(['error' => 'order is empty!']);
        }
        $info = json_encode($products);

        //for case when we have same products multiple times
        $preparedProducts = [];
        foreach ($products as $product){
            $preparedProducts[$product['id']] =  $preparedProducts[$product['id']]??0;
            $preparedProducts[$product['id']] += $product['quantity'];
        }

        //limit number of calling by country
        $counter = Cache::get($countryCode, 0);
        $counter++;
        if($counter > self::MAX_API_CALLING_BY_COUNTRY){
            return response()->json(['error' => 'too many attempts from your country!']);
        }

        $expiresAt = Carbon::now()->addSeconds(1);
        Cache::store('file')->put($countryCode, $counter, $expiresAt);

        //create an order
        $orderArray = [
            'country_code' => $countryCode,
            'info' => $info
        ];
        $order = Order::query()->create($orderArray);

        //create order Items
        foreach ($products as $product){
            OrderItem::query()->create([
                'order_id' => $order->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity']
            ]);
        }

        //calculate total price for output
        $sum = OrderItem::query()
            ->leftJoin('Products as p', 'p.id','=','product_id')
            ->where('order_id',$order->id)
        ->sum(DB::raw('quantity * price'));//cutting edges with raw

        if($sum<10){
            $order->is_active = false;
            $order->save();
            return response()->json(['error' => 'total price is less than minimum, order was deactivated!']);
        }
        return response()->json(['total_price' => $sum]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        $order = Order::query()->findOrFail($id);
        $order->delete();
        return response(null, 204);
    }
}
