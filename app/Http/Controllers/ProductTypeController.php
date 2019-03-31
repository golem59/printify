<?php

namespace App\Http\Controllers;

use App\OrderItem;
use App\ProductType;
use App\Http\Requests\ProductTypeRequest;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return response()->json(ProductType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\ProductType
     */
    public function store(ProductTypeRequest $request)
    {
        $productType = ProductType::query()->create($request->validated());
        return response()->json($productType);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function show(ProductType $productType)
    {
        return response()->json(
            ProductType::query()->findOrFail($productType)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function update(ProductTypeRequest $request, int $id)
    {
        $productType = ProductType::query()->findOrFail($id);
        $productType->fill($request->except(['id']));
        $productType->save();
        return response()->json($productType);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        $productType = ProductType::query()->findOrFail($id);
        if($productType->delete()) {
            return response(null, 204);
        }
    }

    /**
     * return all orders by product_type
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function orders($id)
    {
        $orders = OrderItem::query()
            ->leftJoin('Products as p', 'p.id','=','product_id')
            ->where('p.product_type_id',$id)
            ->distinct()
            ->get(['order_id']);

        return response()->json($orders);
    }
}
