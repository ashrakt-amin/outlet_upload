<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Controllers\Controller;
use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    use TraitsAuthGuardTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->getTokenId('client')) {
            $rate = Rate::where(['item_id'=>$request->item_id, 'client_id'=>$this->getTokenId('client')])->first();
            if ($rate) {
                $rate->rate_degree = $request->rate_degree;
                if ($rate->update()) {
                    return response()->json([
                        "success" => true,
                        "message" => "تم تحديث تقييم المنتج",
                    ], 200);
                } else {
                    return response()->json([
                        "success" => true,
                        "message" => "فشل تحديث تقييم المنتج",
                    ], 200);
                }
            } else {
                $rate = new Rate();
                $rate->item_id     = $request->item_id;
                $rate->client_id   = $this->getTokenId('client');
                $rate->rate_degree = $request->rate_degree;
                if ($rate->save()) {
                    return response()->json([
                        "success" => true,
                        "message" => "تم تقييم المنتج",
                    ], 200);
                } else {
                    return response()->json([
                        "success" => false,
                        "message" => "فشل تقييم المنتج",
                    ], 422);
                }
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "يرجى تسجيل الدخول كعميل",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show(Rate $rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate $rate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        //
    }
}
