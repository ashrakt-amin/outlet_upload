<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\ColorSizeStock;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = Cart::where(['client_id'=> Auth::guard()->id()])->with(['colorSizeStock'])->get();
        return response()->json([
            'data' => CartResource::collection($carts),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->color_id && $request->size_id) {
            $colorSizeStock = ColorSizeStock::where([
                'item_id'=>$request->item_id,
                'color_id'=>$request->color_id,
                'size_id'=>$request->size_id,
            ])->first();
            if ($colorSizeStock->stock >= $request->quantity) {
                $cart = Cart::where(['color_size_stock_id'=>$colorSizeStock->id, 'client_id'=>Auth::guard()->id()])->first();
                if ($cart) {
                    $cart->update($request->all());
                    return response()->json([
                        "success" => true,
                        "message" => "تم تعديل الكمية",
                    ], 200);
                } else {
                    $cart = new Cart();
                    $cart ->color_size_stock_id = $colorSizeStock->id;
                    $cart ->trader_id           = $colorSizeStock->item->trader_id;
                    $cart ->client_id           = Auth::guard()->id();
                    $cart ->quantity            = $request->quantity;
                    if ($cart->save()) {
                        return response()->json([
                            "success" => true,
                            "message" => "تم اضافة الصنف للسلة",
                        ], 200);
                    } else {
                        return response()->json([
                            "success" => false,
                            "message" => "فشل اضافة الصنف للسلة",
                        ], 422);
                    }
                }
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "الرصيد المتاح".' '.$colorSizeStock->stock,
                ], 422);
            }
        }
        // An old
        // $itemCount = ColorSizeStock::find($request->color_size_stock_id);
        // if ($itemCount->stock > $request->quantity) {
        //     $cart = Cart::where(['color_size_stock_id'=>$request->color_size_stock_id, 'client_id'=>Auth::guard()->id()])->first();
        //     if ($cart) {
        //         $cart->update($request->all());
        //         return response()->json([
        //             "success" => true,
        //             "message" => "تم تعديل الكمية",
        //         ], 200);
        //     } else {
        //         $cart = new Cart();
        //         $cart ->color_size_stock_id = $request->color_size_stock_id;
        //         $cart ->trader_id           = $request->trader_id;
        //         $cart ->client_id           = Auth::guard()->id();
        //         $cart ->quantity            = $request->quantity;
        //         if ($cart->save()) {
        //             return response()->json([
        //                 "success" => true,
        //                 "message" => "تم اضافة الصنف للسلة",
        //             ], 200);
        //         } else {
        //             return response()->json([
        //                 "success" => false,
        //                 "message" => "فشل اضافة الصنف للسلة",
        //             ], 422);
        //         }
        //     }
        // } else {
        //     return response()->json([
        //         "success" => false,
        //         "message" => "الرصيد لا يسمح",
        //     ], 422);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        return response()->json([
            'data' => new CartResource($cart),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ColorSizeStock  $colorSizeStock
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if ($request->color_id && $request->size_id) {
            $colorSizeStock = ColorSizeStock::where([
                'item_id'=>$request->item_id,
                'color_id'=>$request->color_id,
                'size_id'=>$request->size_id,
            ])->first();
            if ($colorSizeStock->stock >= $request->stock) {
                return response()->json([
                    "success" => true,
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "الرصيد المتاح".$colorSizeStock->stock,
                ], 422);
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function clientCart(Cart $cart, $client)
    {
        $cart = Cart::where('client_id', $client)->first();
        return response()->json([
            'data' => new CartResource($cart),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        if ($cart->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الرصيد المطلوب",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل الرصيد المطلوب",
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function quantity(Request $request, Cart $cart)
    {
        if ($cart->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الاصناف",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل الاصناف",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        if ($cart) {
            if ($cart->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف المنتج من السلة",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف المنتج من السلة ",
                ], 422);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroyAll()
    {
        $carts = Cart::where(['client_id'=>auth()->guard()->id()])->get();
        if ($carts) {
            foreach($carts as $cart) {
                $cart->delete();
            }
            return response()->json([
                "success" => true,
                "message" => "تم حذف المنتجات من السلة",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف المنتج من السلة ",
            ], 422);
        }
    }
}
