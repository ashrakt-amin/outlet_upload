<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\AdvertisementLink;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Resources\AdvertisementResource;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $advertisements = Advertisement::all();
        foreach ($advertisements as $advertisement) {
            if ($advertisement->advertisement_expire == date('Y-m-d')) {
                $advertisement->renew = $advertisement->renew - 1;
                $advertisement->update();
            }
        }
        $advertisements = Advertisement::with(['trader'])->get();
        return response()->json([
            "data" => AdvertisementResource::collection($advertisements)
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
        $advertisement = new Advertisement();
        $advertisement->fill($request->input());

        // Expire of advertisement
        $contruct_date = Carbon::now();
        $expiry_days =  $request->input('renew') > 0 ? $request->input('renew') * 30 : 30;
        $advertisement->advertisement_expire = Carbon::parse($contruct_date)->addDays(($expiry_days));
        $diff_day = Carbon::now()->diffInDays($advertisement->advertisement_expire, false);
        if ($request->hasFile('img')) {
            $file            = $request->file('img');
            $ext             = $file->getClientOriginalExtension();
            $filename        = time().'.'.$ext;
            $file->move('assets/images/uploads/advertisements/', $filename);
            if ($request->input('id')) {
                $advertisement = Advertisement::where(['id'=>$request->input('id')])->first();
                $image_path = "assets/images/uploads/advertisements/".$advertisement->img;  // Value is not URL but directory file path
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
                $advertisement->img = $filename;
                if ($advertisement->update()) {
                    return response()->json([
                        "success" => true,
                        "message" => "تم تعديل الاعلان",
                        "data" => new AdvertisementResource($advertisement)
                    ], 200);
                }
            }
            $advertisement->img = $filename;
        }
        $advertisement->save();

        if ($advertisement) {
            return response()->json([
                "success"  => true,
                "message"  => "تم تسجيل اعلانا تجاريا جديدا",
                "data"     => new AdvertisementResource($advertisement),
                "daysRemainig" => $diff_day,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل اعلانا تجاريا جديدا",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function show(Advertisement $advertisement)
    {
        return response()->json([
            "data" => AdvertisementResource::collection($advertisement)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Advertisement $advertisement)
    {
        // $current_expire = strtotime($advertisement->advertisement_expire);
        // Expire of advertisement

        $oldExpiryDate   = $advertisement->advertisement_expire;
        $oldExpiryDate   = $oldExpiryDate[5].$oldExpiryDate[6];
        $oldRenew        = $advertisement->renew;
        $newRenew        = $request->input('renew');
        $advertisement->fill($request->input());
        // Expire of advertisement
        if ($request->input('renew') > 0 ) {
            $month     = $oldExpiryDate;
            for ($i=0; $i < $newRenew; $i++) {
                $month = $month+1;
                if ($month < 10) {
                    $month = '0'.$month;
                }
                if ($month > 12) {
                    $month = $month - 12;
                }
                $monthDays = Carbon::now()->month($month)->daysInMonth;
                $advertisement->advertisement_expire = Carbon::parse($advertisement->advertisement_expire)->addDays(($monthDays));
                $diff_day = Carbon::now()->diffInDays($advertisement->advertisement_expire, false);
                $advertisement->update();
            }
        }
        if ($request->hasFile('img')) {
            $image_path = "assets/images/uploads/advertisements/".$advertisement->img;  // Value is not URL but directory file path
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $file            = $request->file('img');
            $ext             = $file->getClientOriginalExtension();
            $filename        = time().'.'.$ext;
            $file->move('assets/images/uploads/advertisements/', $filename);
            $advertisement->img = $filename;
        }
        $advertisement->renew = $newRenew + $oldRenew;
        if ($advertisement->update()) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الاعلان",
                "data" => new AdvertisementResource($advertisement)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل الاعلان",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement)
    {
        if ($advertisement->delete()) {
            return response()->json([
                "success" => true,
                "message" => "تم حذف الاعلان",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف الاعلان",
            ], 422);
        }
    }
}
