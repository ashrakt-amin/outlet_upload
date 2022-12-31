<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\ActivityCollection;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = Activity::all();
        return response()->json([
            "data" => ($activities)
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
        $activity = Activity::create($request->all());
        if ($activity) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل نشاطا تجاريا جديدا",
                "data" => new ActivityResource($activity)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل نشاطا تجاريا جديدا",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        $activity = Activity::where(['id'=>$activity->id])->with(['units','traders'])->first();
        return response()->json([
            "data" => new ActivityResource($activity)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activity $activity)
    {
        if ($activity->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل النشاط التجاري",
                "data" => new ActivityResource($activity)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل النشاط التجاري",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        if ($activity->traders->count() == 0 ) {
            if ($activity->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف النشاط التجاري",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف النشاط",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف النشاط التجاري لارتباطه بتجار",
            ], 422);
        }
    }
}
