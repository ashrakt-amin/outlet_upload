<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderStatu;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderStatuResource;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderResource;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderdetails = OrderDetail::WhereTraderAuth('trader_id')->get();
        return response()->json([
            "data" => OrderDetailResource::collection($orderdetails)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return \Illuminate\Http\Response
     */
    public function show(OrderDetail $orderDetail)
    {
        $orderdetails = OrderDetail::WhereTraderAuth('trader_id')->get();
        return response()->json([
            "data"    => OrderDetailResource::collection($orderdetails)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function trader()
    {
        $orderDetails = OrderDetail::groupBy('order_id')->WhereTraderAuth('trader_id')->pluck('order_id');
        if (count($orderDetails) > 0) {
            foreach ($orderDetails as $orderDetail) {
                $order[] = OrderResource::collection(Order::where(['id'=>$orderDetail])->with(['orderDetails'])->get());
            }
            return response()->json([
                "data"    => $order,
            ]);
        } else {
            return response()->json([
                "success" => true,
                "message" => "لا يوجد اوردرات للتاجر",
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderDetail $orderDetail)
    {
        if ($orderDetail->update($request->all())) {
            $orderStatu = OrderStatu::where(['id'=>$request->order_statu_id])->first();
            if ( ($orderStatu->code == 0)) {
                    $stock = Stock::where(['id'=>$orderDetail->stock_id])->first();
                    $stock->stock = $stock->stock + $orderDetail->quantity;
                    $stock->update();
                return response()->json([
                    "success" => true,
                    "message" => "تم الغاءالاوردر",
                    "data" => new OrderStatuResource(OrderStatu::find($orderDetail->order_statu_id))
                ], 200);
            } else {
                return response()->json([
                    "success" => true,
                    "message" => "تم تعديل حالة الاوردر",
                    "data" => new OrderStatuResource(OrderStatu::find($orderDetail->order_statu_id))
                ], 200);
            }
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل حالة الاوردر",
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function cancelAll(Request $request, OrderDetail $orderDetail)
    {
        if ($request->order_statu_id === 0) {
            $order              = Order::where(['id'=>$orderDetail->order_id])->first();
            $orderDetails       = OrderDetail::where(['order_id'=>$orderDetail->order_id])->get();
            $traderOrderDetails = OrderDetail::where(['order_id'=>$orderDetail->order_id, 'trader_id'=>$orderDetail->trader_id])->get();

            if (count($orderDetails) == count($traderOrderDetails)) {
                $order->update($request->all());
            }

            foreach ($traderOrderDetails as $orderDetail) {
                $orderDetail->order_statu_id = 0;
                $orderDetail->update();

                $stock = Stock::where(['id'=>$orderDetail->stock_id])->first();
                $stock->stock = $stock->stock + $orderDetail->quantity;
                $stock->update();
            }

            if (($orderDetail->order_statu_id == 0)) {
                return response()->json([
                    "success" => true,
                    "message" => "تم الغاء الاوردر",
                    "data" => new OrderStatuResource($orderDetail->orderStatu)
                ], 200);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل الغاء الاوردر",
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, OrderDetail $orderDetail)
    {
        if ($request->order_statu_id == 0) {
                $orderDetail->order_statu_id = 0;
                $orderDetail->update();

                $stock = Stock::where(['id'=>$orderDetail->stock_id])->first();
                $stock->stock = $stock->stock + $orderDetail->quantity;
                $stock->update();
            if (($orderDetail->order_statu_id == 0)) {
                return response()->json([
                    "success" => true,
                    "message" => "تم الغاء الاوردر",
                    "data" => new OrderStatuResource($orderDetail->orderStatu)
                ], 200);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل الغاء الاوردر",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderDetail $orderDetail)
    {
        //
    }
}
