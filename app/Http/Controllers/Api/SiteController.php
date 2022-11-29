<?php

namespace App\Http\Controllers\Api;
use App\Models\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteResource;
use App\Http\Resources\SiteCollection;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = Site::all();
        // return new Collection($projects);
        return response()->json([
            "data" => new SiteCollection($sites)
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
        $site = Site::create($request->all());
        if ($site) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل تميزا جديدة",
                "data" => new SiteResource($site)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل التميز",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        return response()->json([
            "data" => new SiteResource($site)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        if ($site->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل التميز",
                "data" => new SiteResource($site)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل التميز",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        if ($site->delete()) {
            return response()->json([
                "success" => true,
                "message" => "تم حذف التميز",
                "data" => new SiteResource($site)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف التميز",
            ], 422);
        }
    }
}
