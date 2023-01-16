<?php

namespace App\Http\Controllers\Api;

use App\Models\Unit;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UnitResource;
use App\Repository\UnitRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;
use App\Http\Resources\SubResources\UnitItemOffersResource as SubUnitResource;

class UnitController extends Controller
{
    use TraitResponseTrait;
    use TraitsAuthGuardTrait;
    use TraitImageProccessingTrait;

    private $unitRepository;
    public function __construct(UnitRepositoryInterface $unitRepository)
    {
        $this->unitRepository = $unitRepository;

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
        return $this->sendResponse(UnitResource::collection($this->unitRepository->all()), "", 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function latest()
    {
        return $this->sendResponse(UnitResource::collection($this->unitRepository->latest()), "", 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitRequest $request)
    {
        $unit = $this->unitRepository->create($request->validated());
        return $this->sendResponse(new UnitResource($unit), "تم تسجيل ,حدة جديدا", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        return $this->sendResponse(new UnitResource($this->unitRepository->find($unit->id)), "", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function unitOffers(Unit $unit)
    {
        return $this->sendResponse(new SubUnitResource($this->unitRepository->find($unit->id)), "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        $unit = $this->unitRepository->edit($unit->id, $request->validated());
        return $this->sendResponse(new UnitResource($unit), "تم تعديل ال,حدة", 200);
    }

    /**
     * store the activities of the unit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function categories(Request $request, Unit $unit)
    {
        $unit = $this->unitRepository->edit($unit->id, $request->all());
        return $this->sendResponse(new UnitResource($unit), "تم تعديل ال,حدة", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        if (!count($unit->items)) {
            if ($this->unitRepository->delete($unit->id)) return $this->sendResponse("", "تم حذف الوحدة");
        }
        return $this->sendError("لا يمكن حذف مشروعا له فروع", [], 405);
    }
}
