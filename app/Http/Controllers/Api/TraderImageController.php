<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TraderImage;
use Illuminate\Http\Request;

class TraderImageController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TraderImage  $traderImage
     * @return \Illuminate\Http\Response
     */
    public function show(TraderImage $traderImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TraderImage  $traderImage
     * @return \Illuminate\Http\Response
     */
    public function edit(TraderImage $traderImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TraderImage  $traderImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TraderImage $traderImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TraderImage  $traderImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(TraderImage $traderImage)
    {
        $lg_image_path = "traders/lg/".$traderImage->img;  // Value is not URL but directory file path
        $sm_image_path = "traders/sm/".$traderImage->img;  // Value is not URL but directory file path

        $this->deleteImage($lg_image_path);
        $this->deleteImage($sm_image_path);
        if ($traderImage->delete()) {
            return response()->json([
                "success" => true,
                "message" => "تم حذف الصورة",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف الصورة",
            ], 422);
        }
    }
}
