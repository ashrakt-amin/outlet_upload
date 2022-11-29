<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\AdvertisementLink;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertisementLinkResource;

class AdvertisementLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $advertisementLinks = AdvertisementLink::all();
        return response()->json([
            "data" => AdvertisementLinkResource::collection($advertisementLinks)
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
        $advertisementLink = AdvertisementLink::create($request->all());
        if ($advertisementLink) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل رابط اعلان جديد",
                "data" => new AdvertisementLinkResource($advertisementLink)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل رابط اعلان جديد",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdvertisementLink  $advertisementLink
     * @return \Illuminate\Http\Response
     */
    public function show(AdvertisementLink $advertisementLink)
    {
        return response()->json([
            "data" => AdvertisementLinkResource::collection($advertisementLink)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdvertisementLink  $advertisementLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvertisementLink $advertisementLink)
    {
        if ($advertisementLink->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل رابط الاعلان",
                "data" => new AdvertisementLinkResource($advertisementLink)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل رابط الاعلان",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdvertisementLink  $advertisementLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvertisementLink $advertisementLink)
    {
        if ($advertisementLink->delete()) {
            return response()->json([
                "success" => true,
                "message" => "تم حذف رابط الاعلان",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف رابط الاعلان",
            ], 422);
        }
    }
}
