<?php

namespace App\Http\Controllers\Api;

use App\Models\ItemUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemUnitCollection;

class ItemUnitController extends Controller
{
    public function __construct ()
    {
        $authorizationHeader = \request()->header('Authorization');
        if(request()->bearerToken() != null) {
            $this->middleware('auth:sanctum');
        };
        // if(isset($authorizationHeader)) {
        //     $this->middleware('auth:sanctum');
        // };
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itemUnits = ItemUnit::all();
        return response()->json([
            "data" => new ItemUnitCollection($itemUnits)
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
        $itemUnit = ItemUnit::create($request->all());
        if ($itemUnit) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل وحدة منتج جديدة",
                "data" => $itemUnit
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل وحدة منتج جديدة",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemUnit  $itemUnit
     * @return \Illuminate\Http\Response
     */
    public function show(ItemUnit $itemUnit)
    {
        return response()->json([
            "data" => new ItemUnitCollection($itemUnit)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemUnit  $itemUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemUnit $itemUnit)
    {
        if ($itemUnit->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل وحدة المنتج ",
                "data" => $itemUnit
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل وحدة المنتج ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemUnit  $itemUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemUnit $itemUnit)
    {
        if ($itemUnit->items->count() == 0) {
            if ($itemUnit->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف وحدة المنتج ",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف وحدة المنتج ",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف وحدة منتج داخل منتجات",
            ], 422);
        }
    }
}
