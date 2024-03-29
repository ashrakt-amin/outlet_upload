<?php

namespace App\Http\Controllers\Api;

use App\Models\Trader;
use Illuminate\Http\Request;
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
     * @param  \App\Http\Requests\TraderRequest  $request
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
     * @param  \App\Http\Requests\TraderRequest  $request
     * @param  \App\Models\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function update(TraderRequest $request, Trader $trader)
    {
        return $this->sendResponse(new TraderResource($this->traderRepository->edit($trader->id, $request->validated())), "تم تعديل التاجر", 202);

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
                if ($trader->update()) return $this->sendResponse(new TraderResource($trader), "تم تعديل التاجر", 202);
            });
        } else {
            return $this->sendResponse("", "تسجيل الدخول ", 422);
        }
    }
    /**
    * @param $id
    * @return Trader
    */
    public function forgettingPassword(Request $request)
    {
        return $this->sendResponse($this->traderRepository->forgettingPassword($request->all()), "تم تعديل الباسورد", 200);
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
             return $this->sendResponse("", "تم حذف التصنيف", 200);
        }
    }

    /**
     * restore single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        return $this->sendResponse($this->traderRepository->restore($id), "", 200);
    }

    /**
     * restore all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function restoreAll()
    {
        return $this->sendResponse($this->traderRepository->restoreAll(), "", 200);
    }

    /**
     * force delete single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        return $this->sendResponse($this->traderRepository->forceDelete($id), "", 200);
    }

    /**
     * force delete all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function forceDeleteAll()
    {
        return $this->sendResponse($this->traderRepository->forceDeleteAll(), "", 200);
    }
}
