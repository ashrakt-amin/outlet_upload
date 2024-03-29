<?php

namespace App\Http\Controllers\Api;

use App\Models\Level;
use App\Models\LevelImage;
use Illuminate\Http\Request;
use App\Http\Requests\LevelRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\LevelResource;
use App\Repository\LevelRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class LevelController extends Controller
{
    use TraitsAuthGuardTrait;
    use TraitImageProccessingTrait;
    use TraitResponseTrait;

    private $levelRepository;
    public function __construct(LevelRepositoryInterface $levelRepository)
    {
        $this->levelRepository = $levelRepository;
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
        return $this->sendResponse(LevelResource::collection($this->levelRepository->all()), "", 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allConditons(Request $request)
    {
        return $this->levelRepository->forAllConditionsReturn($request->all(), LevelResource::class);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LevelRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LevelRequest $request)
    {
        $level = $this->levelRepository->create($request->validated());
        return $this->sendResponse(new LevelResource($level), "تم تسجيل طابقا جديدا", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function show(Level $level)
    {
        return $this->sendResponse(new LevelResource($this->levelRepository->find($level->id)), "", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function client(Level $level)
    {
        $level = Level::where('id', $level->id)->with(['traders', 'activities'])->get();
        return response()->json([
            "data"=> LevelResource::collection($level),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function update(LevelRequest $request, Level $level)
    {
        return $this->sendResponse(new LevelResource($this->levelRepository->edit($level->id, $request->validated())), "تم تعديل المشروع");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function destroy(Level $level)
    {
        if (!count($level->units)) {
            $this->levelRepository->delete($level->id);
            return $this->sendResponse("", "تم حذف المشروع", 200);
        }
        return $this->sendError("لا يمكن حذف مكانا له أفرع", [], 200);
    }

    /**
     * restore single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        return $this->sendResponse($this->levelRepository->restore($id), "", 200);
    }

    /**
     * restore all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function restoreAll()
    {
        return $this->sendResponse($this->levelRepository->restoreAll(), "", 200);
    }

    /**
     * force delete single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        return $this->sendResponse($this->levelRepository->forceDelete($id), "", 200);
    }

    /**
     * force delete all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function forceDeleteAll()
    {
        return $this->sendResponse($this->levelRepository->forceDeleteAll(), "", 200);
    }
}
