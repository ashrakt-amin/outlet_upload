<?php

namespace App\Http\Controllers\Api;
use App\Models\Unit;

use App\Models\Statu;
use App\Models\Activity;
use App\Models\UnitImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UnitResource;
use App\Http\Resources\StatuResource;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class UnitController extends Controller
{
    use TraitImageProccessingTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::all();
        // return new Collection($projects);
        return response()->json([
            "data" => UnitResource::collection($units)
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function latest()
    {
        $levels = Unit::latest()->take(10)->get();
        return response()->json([
            "data" => UnitResource::collection($levels),
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
        $unit = Unit::create($request->all());
            if ($unit) {
                if ($request->hasFile('img')) {
                    foreach ($request->file('img') as $image) {
                        $originalFilename = $this->setImage($image, $unit->id, 'units/lg');
                        $filename = $this->aspectForResize($image, $unit->id, 450, 450, 'units/sm');
                        $image = new UnitImage();
                        $image->unit_id = $unit->id;
                        $image->img     = $filename;
                        $image->save();
                    }
                }
                return response()->json([
                    "success" => true,
                    "message" => "تم تسجيل وحدة جديدة",
                    "data" => new UnitResource($unit)
                ], 200);
            } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل الوحدة",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        $unit = Unit::where(['id'=>$unit->id])->with('trader')->first();
        $next_Statu = Statu::where('id', '>', $unit->statu_id)->first();
        return response()->json([
            "data"       => new UnitResource($unit),
            "next_Statu" => $next_Statu ? new StatuResource($next_Statu) : false,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        if ($unit->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الوحدة",
                "data" => UnitResource::collection($unit)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل الوحدة",
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Unit $unit)
    {
        $status = Statu::all();
        if (count($status) == 0) {
            $statu = new Statu();
            $statu->name = "حجز";
            $statu->save();
            $statu = new Statu();
            $statu->name = "اتمام تعاقد";
            $statu->save();
        }
        if ($unit->update($request->all())) {
            // $pivots = DB::table('level_trader')->where(['level_id'=>$unit->level_id, 'trader_id'=>$unit->trader_id, 'unit_id'=>$unit->id])->get();
            // if (count($pivots) == 0) {
            //     $level = Level::find($unit->level_id);
            //     $level->traders()->attach([$unit->trader_id], ['unit_id'=> $unit->id]);
            //     dd($level);
            // }
            return response()->json([
                "success" => true,
                "message" => "حالة الوحدة".''.$unit->statu->name,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تغيير حالة الوحدة",
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function activities(Request $request)
    {
        $activities = $request->activity_id;
        foreach ($activities as $key => $value) {
            $pivot = DB::table('activity_trader')->where(['activity_id'=>$value['id'], 'unit_id'=>$request->unit_id, 'trader_id'=>$request->trader_id])->first();
            if ($pivot == null) {
                $activity = Activity::find($value['id']);
                $activity->traders()->attach(['trader_id'=>$request->trader_id], ['unit_id'=>$request->unit_id]);
            }
        }
        $unit = DB::table('activity_trader')->where(['unit_id'=>$request->unit_id, 'trader_id'=>$request->trader_id])->count();
        if ( $unit > 0 ) {
            return response()->json([
                "success" => true,
                "message" => "تم اضافة انشطة الوحدة",
                "data" => new UnitResource($unit)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل اضافة انشطة الوحدة",
            ], 422);
        }
        // update pivot table
        Activity::find($value['activity_id'])->traders()->updateExistingPivot($request->trader_id, ['unit_id'=>$value['unit_id']]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function updateActivity(Request $request, Unit $unit)
    {
            $pivot = DB::table('activity_trader')->where(['activity_id'=>$request->activity_id, 'trader_id'=>$request->trader_id, 'unit_id'=>$unit->id])->first();
            if ($pivot) {
                return response()->json([
                    "success" => true,
                    "message" => "نشاطا مضافا من قبل",
                ], 200);
            }
            // update pivot table
            $updateActivity = Activity::find($request->activity_id)->traders()->updateExistingPivot([$unit->trader_id], ['unit_id'=>$unit->id]);
        if ($updateActivity) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل انشطة الوحدة",
                "data" => $unit->statu->name
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل انشطة الوحدة",
            ], 422);
        }
        // delete pivot row
        $activity = Activity::find($request->activity_id);
        $activity->roles()->detach([$unit->trader_id], ['unit_id'=>$unit->id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function finance(Request $request, Unit $unit)
    {
        if ($unit->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم اختيار نظام دفع الوحدة",
                "data" => $unit->finance_id
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل اختيار نظام دفع الوحدة",
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function deposit(Request $request, Unit $unit)
    {
        if ($unit->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل أشهر التأمين الوحدة",
                "data" => $unit->deposit
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل أشهر التأمين الوحدة",
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function rents(Request $request, Unit $unit)
    {
        if ($unit->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل عدد أشهر ايجار الوحدة",
                "data" => $unit->rents_count
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل عدد أشهر ايجار الوحدة",
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function discount(Request $request, Unit $unit)
    {
        if ($unit->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم اضافة خصم الوحدة",
                "data" => $unit->discount
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل اضافة خصم الوحدة",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        if ($unit->trader_id == 0) {
            $unit->delete();
            return response()->json([
                "success" => true,
                "message" => "تم حذف الوحدة",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف الوحدة",
            ], 422);
        }
    }
}
