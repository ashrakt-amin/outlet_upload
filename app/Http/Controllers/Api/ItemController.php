<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Item\ItemFlashSalesResource;
use App\Http\Resources\ItemResource;
use App\Repository\ItemRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class ItemController extends Controller
{
    use TraitResponseTrait;
    use TraitsAuthGuardTrait;
    use TraitImageProccessingTrait;

    private $itemRepository;
    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;

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
        return $this->sendResponse(ItemFlashSalesResource::collection($this->itemRepository->search($request->all()))->paginate(12), "", 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        $item = $this->itemRepository->create($request->validated());
        return $this->sendResponse(new ItemFlashSalesResource($item), "تم تسجيل ,منتجا جديدا", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return $this->sendResponse(new ItemResource($this->itemRepository->find($item->id)), "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ItemRequest  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, Item $item)
    {
        if ($this->getTokenId('user')) {
            $item = $this->itemRepository->edit($item->id, $request->validated());
            return $this->sendResponse(new ItemFlashSalesResource($item), "تم تعديل ال,حدة", 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        if (count($item->stocks)) {
            $this->deleteImages($item->itemImages()->pluck('img')->toArray(), 'items');
            $item->itemImages()->delete($item->id);
            if ($this->itemRepository->delete($item->id)) return $this->sendResponse("", "تم حذف المنتج");
        }
        return $this->sendError("لا يمكن حذف منتجا له رصيد", [], 405);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function latest()
    {
        return $this->sendResponse(ItemResource::collection($this->itemRepository->latest())->paginate(4), "", 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function random()
    {
        return $this->sendResponse(ItemResource::collection($this->itemRepository->random()), "", 200);
    }

    /**
     * store the activities of the item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function categories(Request $request, Item $item)
    {
        $item = $this->itemRepository->edit($item->id, $request->all());
        return $this->sendResponse(new ItemFlashSalesResource($item), "تم تعديل الوحدة", 200);
    }

    /**
     * Get items of a project.
     *
     * @return Collection
     */
    public function itemsForAllConditions(Request $request)
    {
        $items = $this->itemRepository->itemsForAllConditionsPaginate($request->all());
        return !array_key_exists('paginate', $request->all()) ?
            $this->sendResponse(ItemFlashSalesResource::collection($this->itemRepository->itemsForAllConditionsRandom($request->all())), "Random items; Youssof", 200)
            :
            $this->paginateResponse(ItemFlashSalesResource::collection($items), $items, "paginate items; Youssof", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function toggleFlashSales(Item $item)
    {
        $item = $this->itemRepository->toggleUpdate($item->id);
        return $this->sendResponse($item->flash_sales,
        $item->flash_sales == true ? "الازالة من العروض السريعة" : "الاضافة للعروض السريعة" ,
        201);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function flashSales(Request $request)
    {
        return $this->sendResponse(ItemFlashSalesResource::collection($this->itemRepository->itemsForAllConditions($request->all())), "", 200);
    }
}
