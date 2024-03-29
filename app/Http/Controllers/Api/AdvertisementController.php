<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisementRequest;
use App\Http\Resources\AdvertisementResource;
use App\Repository\AdvertisementRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class AdvertisementController extends Controller
{
    use TraitImageProccessingTrait;
    use TraitResponseTrait;
    private $advertisementRepository;
    public function __construct(AdvertisementRepositoryInterface $advertisementRepository)
    {
        $this->advertisementRepository = $advertisementRepository;
        if(request()->bearerToken() != null) {
            return $this->middleware('auth:sanctum');
        };
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $advertisements = Advertisement::all();
        // foreach ($advertisements as $advertisement) {
        //     $contruct_expire = Carbon::parse($advertisement->advertisement_expire)->diffForHumans();
        //     $diff_day = Carbon::now()->diffInDays($advertisement->advertisement_expire, false);
        //     if ($advertisement->advertisement_expire == date('Y-m-d') && $advertisement->renew > 0) {
        //         $advertisement->renew = $advertisement->renew - 1;
        //         $advertisement->update();
        //     } elseif ($contruct_expire == "1 week ago") {
        //         $advertisement->delete();
        //     }
        // }
        return $this->sendResponse(AdvertisementResource::collection($this->advertisementRepository->advertisementsWhereallConditions($request->all())), "اعلانات الرئيسية", 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $data = $request->validated();
        // $advertisement = $this->advertisementRepository->create($data);
        // return $this->sendResponse(new AdvertisementResource($advertisement), "تم تسجيل اعلانا جديدا", 201);


        $advertisement = new Advertisement();
        $request['renew'] > 0 ?: $request['renew'] = 1;
        $advertisement->fill($request->input());

        $expiry_days = $request->input('renew') > 0 ? $request->input('renew') * 30 : 30;
        $advertisement->advertisement_expire = Carbon::parse(Carbon::now())->addDays(($expiry_days));
        if ($request->has('img')) {
            $img = $request->file('img');
            $filename = $this->setImage($img, 'advertisements', 900, 600);
            $advertisement->img = $filename;
            if ($request->input('id')) {
                $advertisement = Advertisement::where(['id'=>$request->input('id')])->first();
                if ($advertisement->img != $filename) {
                    $this->deleteImage(Advertisement::IMAGE_PATH, $advertisement->img);
                }
                $advertisement->img = $filename;
                $advertisement->update();
            return $this->sendResponse(new AdvertisementResource($advertisement), "تم تعديل الاعلان", 200);
            }
            $advertisement->img = $filename;
        }
        $advertisement->save();
        return $this->sendResponse(new AdvertisementResource($advertisement), "تم تسجيل اعلانا جديدا", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function grade(Request $request)
    {
        return $this->sendResponse(AdvertisementResource::collection($this->advertisementRepository->advertisementsWhereallConditions($request->all())), "", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function show(Advertisement $advertisement)
    {
        return $this->sendResponse(new AdvertisementResource($this->advertisementRepository->find($advertisement->id)), "", 200);
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
        return $this->sendResponse(new AdvertisementResource($this->advertisementRepository->edit($advertisement->id, $request->all())), "تم تعديل الاعلان", 200);


        // $current_expire = strtotime($advertisement->advertisement_expire);
        // return DB::transaction(function() use($advertisement, $request){

        //     $oldExpiryDate   = $advertisement->advertisement_expire;
        //     $month           = $oldExpiryDate[5].$oldExpiryDate[6];
        //     $oldRenew        = $advertisement->renew;
        //     $newRenew        = $request->input('renew');
        //     $advertisement->fill($request->input());
        //     // Expire of advertisement
        //     if ($request->input('renew') > 0 ) {
        //         for ($i=0; $i < $newRenew; $i++) {
        //             $month = $month + 1;
        //             if ($month < 10) {
        //                 $month = '0'.$month;
        //             }
        //             if ($month > 12) {
        //                 $month = $month - 12;
        //             }
        //             $monthDays = Carbon::now()->month($month)->daysInMonth;
        //             $advertisement->advertisement_expire = Carbon::parse($advertisement->advertisement_expire)->addDays(($monthDays));
        //             $diff_day = Carbon::now()->diffInDays($advertisement->advertisement_expire, false);
        //             $advertisement->update();
        //         }
        //     }
        //     if ($request->hasFile('img')) {
        //         $this->deleteImage(Advertisement::IMAGE_PATH, $advertisement->img);
        //         $img = $request->file('img');
        //         $advertisement->img = $this->setImage($img, 'advertisements', 450, 450);
        //     }
        //     $advertisement->renew = $newRenew + $oldRenew;
        //     $advertisement->update();
        //     $this->sendResponse(new AdvertisementResource($advertisement), "", 200);
        // });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement)
    {
        if ($this->advertisementRepository->delete($advertisement->id)) return $this->sendResponse("", "تم حذف الاعلان");
    }

    /**
     * restore single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        return $this->sendResponse($this->advertisementRepository->restore($id), "", 200);
    }

    /**
     * restore all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function restoreAll()
    {
        return $this->sendResponse($this->advertisementRepository->restoreAll(), "", 200);
    }

    /**
     * force delete single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        return $this->sendResponse($this->advertisementRepository->forceDelete($id), "", 200);
    }

    /**
     * force delete all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function forceDeleteAll()
    {
        return $this->sendResponse($this->advertisementRepository->forceDeleteAll(), "", 200);
    }
}
