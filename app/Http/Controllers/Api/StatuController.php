<?php

namespace App\Http\Controllers\Api;
use App\Models\Statu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StatuCollection;
use App\Http\Resources\StatuResource;

class StatuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = Statu::all();
        // return new Collection($projects);
        return response()->json([
            "data" => new StatuCollection($status)
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
        $statu = Statu::create($request->all());
        if ($statu) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل حالة جديدة",
                "data" => new StatuResource($statu)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل الحالة",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Statu  $statu
     * @return \Illuminate\Http\Response
     */
    public function show(Statu $status)
    {
        return response()->json([
            "data"=> new StatuResource($status),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Statu  $statu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Statu $status)
    {
        if ($status->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل حالة",
                "data" => new StatuResource($status)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل الحالة",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Statu  $statu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Statu $status)
    {
        if ($status->delete()) {
            return response()->json([
                "success" => true,
                "message" => "تم حذف حالة",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف الحالة",
            ], 422);
        }
    }
}
