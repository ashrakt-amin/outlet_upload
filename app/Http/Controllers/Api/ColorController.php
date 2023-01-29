<?php
namespace App\Http\Controllers\Api;

use App\Models\Color;
use App\Http\Requests\ColorRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ColorResource;
use App\Repository\ColorRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class ColorController extends Controller
{
    use TraitResponseTrait;
    private $colorRepository;
    public function __construct(ColorRepositoryInterface $colorRepository)
    {
        $this->colorRepository = $colorRepository;
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
        return $this->sendResponse(ColorResource::collection($this->colorRepository->all()), "", 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ColorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ColorRequest $request)
    {
        $color = $this->colorRepository->create($request->validated());
        return $this->sendResponse(new ColorResource($color), "تم تسجيل لونا جديدا", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\  $color
     * @return \Illuminate\Http\Response
     */
    public function show(Color $color)
    {
        return $this->sendResponse(new ColorResource($color), "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ColorRequest  $request
     * @param  \App\Models\  $color
     * @return \Illuminate\Http\Response
     */
    public function update(ColorRequest $request, Color $color)
    {
        $color = $this->colorRepository->edit($color->id, $request->validated());
        return $this->sendResponse(new ColorResource($color), "تم تعديل اللون");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\  $color
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color $color)
    {
        if (!count($color->stocks)) {
            if ($this->colorRepository->delete($color->id)) return $this->sendResponse("", "تم حذف اللون", 200);
        }
        return $this->sendError("لا يمكن حذف لونا له رصيد", [], 405);
    }
}
