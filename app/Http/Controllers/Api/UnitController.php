<?php

namespace App\Http\Controllers\Api;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UnitResource;
use App\Repository\UnitRepositoryInterface;
use App\Http\Resources\Unit\UnitShowResource;
use App\Http\Resources\Unit\UnitWithoutItemsResource;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

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
    public function index(Request $request)
    {
        return $this->unitRepository->unitsForAllConditionsReturn($request->all(), UnitWithoutItemsResource::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitRequest $request)
    {
        $unit = $this->unitRepository->create($request->validated());
        return $this->sendResponse(new UnitShowResource($unit), "تم تسجيل ,حدة جديدا", 200);
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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        $unit = $this->unitRepository->edit($unit->id, $request->validated());
        return $this->sendResponse(new UnitResource($unit), "تم تعديل الوحدة", 200);
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
            if ($this->unitRepository->delete($unit->id)) return $this->sendResponse("", "تم حذف الوحدة", 200);
        }
        return $this->sendError("لا يمكن حذف مشروعا له فروع", [], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function showOnline(Unit $unit)
    {
        return $this->sendResponse(new UnitShowResource($this->unitRepository->find($unit->id)), "", 200);
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
        return $this->sendResponse(new UnitResource($unit), "تم تعديل ال,حدة", 202);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function toggleUpdate($id, $booleanName)
    {
        $unit = $this->unitRepository->toggleUpdate($id, $booleanName);
        return $this->sendResponse($unit[$booleanName], $booleanName. ' ' .$unit[$booleanName] , 201);
    }
}
