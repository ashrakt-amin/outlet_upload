<?php

namespace App\Http\Controllers\Api;

use App\Models\Trader;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TraderRequest;
use App\Http\Resources\TraderResource;
use App\Repository\TraderRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class TraderController extends Controller
{
    use TraitResponseTrait;
    use TraitsAuthGuardTrait;
    use TraitImageProccessingTrait;

    private $traderRepository;
    public function __construct(TraderRepositoryInterface $traderRepository)
    {
        $this->traderRepository = $traderRepository;
        if(request()->bearerToken() != null) {
            return $this->middleware('auth:sanctum');
        };
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(TraderResource::collection($this->traderRepository->all()), "", 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\TraderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TraderRequest $request)
    {
        $data = $request->validated();
        $trader = $this->traderRepository->create($data);
        return $this->sendResponse(new TraderResource($trader), "تم تسجيل تاجرا جديدا", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function show(Trader $trader)
    {
        return $this->sendResponse(new TraderResource($this->traderRepository->find($trader->id)), "", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function trader()
    {
        return $this->sendResponse(new TraderResource($this->traderRepository->trader()), "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\TraderRequest  $request
     * @param  \App\Models\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function update(TraderRequest $request, Trader $trader)
    {
        $trader = $this->traderRepository->edit($trader->id, $request->validated());
        return $this->sendResponse(new TraderResource($trader), "تم تعديل التاجر");

        if ($this->getTokenId('user') || $this->getTokenId('trader')) {
            return DB::transaction(function() use($trader, $request){
                $age = $trader->age;
                $trader->fill($request->input());
                if ($request->hasFile('img')) {
                    $this->deleteImage(Trader::IMAGE_PATH, $trader->img);
                    $img = $request->file('img');
                    $trader->img = $this->setImage($img, 'traders', 450, 450);
                }
                if ($request->age != null) {
                    $trader->age = $request->age;
                } else {
                    $trader->age = $age;
                }
                if ($trader->update()) {
                    return response()->json([
                        "success" => true,
                        "message" => "تم تعديل التاجر",
                        "data" => new TraderResource($trader),
                    ], 200);
                } else {
                    return response()->json([
                        "success" => false,
                        "message" => "فشل تعديل التاجر",
                    ], 422);
                }
            });
        } else {
            return response()->json([
                "success" => false,
                "message" => "تسجيل الدخول ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trader $trader)
    {
        if (!$trader->units->exists()) {
            $this->traderRepository->delete($trader->id);
             return $this->sendResponse("", "تم حذف التصنيف");
        }
    }
}
