<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Traits\TimeTricksTrait as TraitTimeTricksTrait;

class CouponController extends Controller
{
    use TraitTimeTricksTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::all();
        return response()->json([
            'data' => $coupons
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
        // Available alpha caracters
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // generate a pin based on 2 * 7 digits + a random character
        $pin = mt_rand(100, 999)
            . $characters[rand(0, strlen($characters) - 1)]
            . $characters[rand(0, strlen($characters) - 1)]
            . $characters[rand(0, strlen($characters) - 1)];
        // shuffle the result
        $code = str_shuffle($pin);
        $coupon = new Coupon();
        $coupon->fill($request->input());
        $coupon->code = $code;
        $coupon->expiring_date = $this->timeDiffInDays($request->expiring_date);
        if ($coupon->save()) {
            return response()->json([
                "success" => true,
                "message" => "تم التسجيل",
                "data"    => $coupon
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل التسجيل",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        return response()->json([
            "success" => true,
            "data"    => $coupon
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        if ($coupon->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم التعديل",
                "data"    => $coupon
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل التعديل",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        if ($coupon->delete()) {
            return response()->json([
                "success" => true,
                "message" => "تم الحذف",
                "data"    => $coupon
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل الحذف",
            ], 422);
        }
    }
}
