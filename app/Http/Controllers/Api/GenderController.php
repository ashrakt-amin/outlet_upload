<?php

namespace App\Http\Controllers\Api;

use App\Models\Gender;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenderResource;
use App\Http\Resources\GenderCollection;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genders = Gender::all();
        return response()->json([
                'data' => GenderResource::collection($genders)
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
        $gender = Gender::create($request->all());
        if ($gender) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل نوعا جديدا",
                "data" => $gender
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
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function show(Gender $gender)
    {
        return response()->json([
            "success" => true,
            "data" => new GenderResource($gender)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gender $gender)
    {
        if ($gender->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل النوع",
                "data" => $gender
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
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gender $gender)
    {
        if ($gender->delete()) {
            return response()->json([
                "success" => true,
                "message" => "تم حذف نوعا ",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف النوع",
            ], 422);
        }
    }
}
