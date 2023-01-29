<?php
namespace App\Http\Controllers\Api;

use App\Models\Size;
use App\Http\Requests\SizeRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\SizeResource;
use App\Repository\SizeRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class SizeController extends Controller
{
    use TraitResponseTrait;
    private $sizeRepository;
    public function __construct(SizeRepositoryInterface $sizeRepository)
    {
        $this->sizeRepository = $sizeRepository;
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
        return $this->sendResponse(SizeResource::collection($this->sizeRepository->all()), "", 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\SizeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SizeRequest $request)
    {
        $size = $this->sizeRepository->create($request->validated());
        return $this->sendResponse(new SizeResource($size), "تم تسجيل حجما جديدا", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\  $size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
        return $this->sendResponse(new SizeResource($size), "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\SizeRequest  $request
     * @param  \App\Models\  $size
     * @return \Illuminate\Http\Response
     */
    public function update(SizeRequest $request, Size $size)
    {
        $size = $this->sizeRepository->edit($size->id, $request->validated());
        return $this->sendResponse(new SizeResource($size), "تم تعديل الحجم", 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy(Size $size)
    {
        if (!count($size->stocks)) {
            if ($this->sizeRepository->delete($size->id)) return $this->sendResponse("", "تم حذف الحجم", 204);
        }
        return $this->sendError("لا يمكن حذف حجما له رصيد", [], 405);
    }
}
