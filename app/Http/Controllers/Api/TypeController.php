<?php

namespace App\Http\Controllers\Api;

use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TypeResource;
use App\Http\Resources\TypeCollection;
use App\Models\Item;

class TypeController extends Controller
{

    public function __construct ()
    {
        $authorizationHeader = \request()->header('Authorization');
        if(request()->bearerToken() != null) {
            $this->middleware('auth:sanctum');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();
        return response()->json([
                'data' => TypeResource::collection($types)
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
        $type = Type::create($request->all());
        if ($type) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل نوعا جديدا",
                "data" => $type
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل النوع",
            ], 422);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        // $type = Type::whereHas('gender', function ($gender) {
        //     $gender->where('code', 1);
        // })->with(['gender'])->get();
        return response()->json([
            "success" => true,
            "data" => new TypeResource($type),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        if ($type->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل النوع",
                "data" => new TypeResource($type)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل النوع",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        if ($type->items->count() == 0) {
            if ($type->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف النوع",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف النوع",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف نوعا به منتجات",
            ], 422);
        }
    }
}
