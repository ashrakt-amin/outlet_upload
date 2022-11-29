<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderStatu;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderStatuResource;
use App\Models\ColorSizeStock;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::where(['client_id'=>auth()->guard()->id()])->with(['orderDetails', 'orderStatu'])->get();

        return response()->json([
            "data"    => OrderResource::collection($orders)
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
        // ave in  order table
        $order = new Order();
        $order ->address           = $request->address;
        $order ->finance_id        = $request->finance_id;
        $order ->client_id         = Auth::guard()->id();
        $order ->total             = $request->total;
        $order ->order_statu_id   = $request->order_statu_id;
        $order->save();
        // Save in  order_details table
        $order_details = $request->order_details;
        foreach ($order_details as $key => $value) {
            $orderDetail = new OrderDetail();
            $orderDetail ->order_id            = $order->id;
            $orderDetail ->color_size_stock_id = $value['color_size_stock_id'];
            $orderDetail ->trader_id           = $value['trader_id'];
            $orderDetail ->discount            = $value['discount'];
            $orderDetail ->quantity            = $value['quantity'];
            $orderDetail ->item_price          = $value['item_price'];
            $orderDetail ->order_statu_id      = $order ->order_statu_id;
            $orderDetail ->save();
            $discount[] = $orderDetail ->discount;


            $colorSizeStock = ColorSizeStock::where(['id'=>$orderDetail->color_size_stock_id])->first();
            $colorSizeStock->stock = $colorSizeStock->stock - $orderDetail ->quantity;
            $colorSizeStock->update();
            // $item = Item::where(['id'=>$orderDetail->item_id])->first();
            // $item->stock = $item->stock - $orderDetail ->quantity;
            // $item->update();
        }
        // delete all carts by "scopeWhereAuth"
        $carts = Cart::WhereAuth('client_id')->get();
        foreach ($carts as $cart) {
            $cart->delete();
        }
        if ($order) {
            return response()->json([
                "success"  => true,
                "message"  => "تم طلب الاوردر",
                "data"     => $order,
                "discount" => array_sum($discount),
                "data2"    => $order_details,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل طلب الاوردر ",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $order = Order::whereAuth('client_id')->where(['id'=>$order->id])->with(['orderDetails', 'orderStatu'])->first();
        // $order = Order::where(['client_id'=>auth()->guard()->id()])->with('orderDetails')->get();
        return response()->json([
            "data"     => new OrderResource($order),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trader()
    {
        $orders = Order::where(['client_id'=>auth()->guard()->id()])->with(['orderDetails', 'orderStatu'])->get();

        return response()->json([
            "data"    => OrderResource::collection($orders)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function client()
    {
        $orders = Order::where(['client_id'=>auth()->guard()->id()])->with(['orderDetails', 'orderStatu'])->get();

        return response()->json([
            "data"    => OrderResource::collection($orders)
        ]);

        // $orders = OrderDetail::select(
        //                     "traders.f_name as trader_f_name",
        //                     "traders.m_name as trader_m_name",
        //                     "traders.l_name as trader_l_name",
        //                     "order_details.item_id",
        //                     "items.name as item_name",
        //                     "items.img as item_img",
        //                     "items.size_id as item_size",
        //                     "items.color_id as item_color",
        //                     "items.sale_price as item_sale_price",
        //                     "items.code as item_code",
        //                     // "colors.name as color",
        //                     // "sizes.name as size",
        //                     "orders.address",
        //                     "order_details.discount",
        //                     "order_details.quantity",
        //                     "order_details.item_price",
        //                     "orders.client_id as clientId"
        //                 )
        //                 ->join("orders", "orders.id", "=", "order_details.order_id")
        //                 ->where('client_id', '=', auth()->guard()->id())
        //                 ->join("items", "items.id", "=", "order_details.item_id")
        //                 ->join("traders", "traders.id", "=", "order_details.trader_id")
        //                 // ->join("sizes", "sizes.id", "=", "items.size_id")
        //                 // ->join("colors", "colors.id", "=", "items.color_id")
        //                 ->get()
        //                 ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        if ($order->update($request->all())) {
            $orderStatu = OrderStatu::where(['id'=>$request->order_statu_id])->first();
            $orderDetails = OrderDetail::where(['order_id'=>$order->id])->get();
            foreach ($orderDetails as $orderDetail) {
                $orderDetail->order_statu_id = $request->order_statu_id;
                $orderDetail->update();
                if ( ($orderStatu->code == 0)) {
                    $colorSizeStock = ColorSizeStock::where(['id'=>$orderDetail->color_size_stock_id])->first();
                    $colorSizeStock->stock = $colorSizeStock->stock + $orderDetail->quantity;
                    $colorSizeStock->update();
                }
            }
            if ( ($orderStatu->code == 0)) {
                return response()->json([
                    "success" => true,
                    "message" => "تم الغاءالاوردر",
                    "data" => new OrderStatuResource($order->orderStatu)
                ], 200);
            } else {
                return response()->json([
                    "success" => true,
                    "message" => "تم تعديل حالة الاوردر",
                    "data" => new OrderStatuResource(OrderStatu::find($order->order_statu_id))
                ], 200);
            }
        } else {
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
    public function cancel(Request $request, Order $order)
    {
        if ($request->order_statu_id == 0) {
            $order->update($request->all());
            $orderDetails = OrderDetail::where(['order_id'=>$order->id])->get();
            foreach ($orderDetails as $orderDetail) {
                $orderDetail->order_statu_id = 0;
                $orderDetail->update();

                $colorSizeStock = ColorSizeStock::where(['id'=>$orderDetail->color_size_stock_id])->first();
                $colorSizeStock->stock = $colorSizeStock->stock + $orderDetail->quantity;
                $colorSizeStock->update();
            }
            if (($order->order_statu_id == 0)) {
                return response()->json([
                    "success" => true,
                    "message" => "تم الغاء الاوردر",
                    "data" => new OrderStatuResource($order->orderStatu)
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $orderDetails = OrderDetail::select('order_statu_id')->where(['order_id'=>$order->id])->get();
        foreach ($orderDetails as $orderDetail) {
                $code[] = $orderDetail->orderStatu->code;
        }
        if ( ($order->orderStatu->code == 0) && !in_array(1, $code) ) {
            if ($order->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف الاوردر",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف الاوردر ",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "الاوردر به منتجات تم بيعها ولا يمكن حذفه",
            ], 422);
        }
    }
}
