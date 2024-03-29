<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Repository\ItemRepositoryInterface;
use App\Http\Resources\Item\ItemFlashSalesResource;
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
        return $this->itemRepository->forAllConditionsReturn($request->all(), ItemFlashSalesResource::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function archived(Request $request)
    {
        return $this->itemRepository->archived($request->all(), ItemFlashSalesResource::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ItemRequest  $request
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
        return $this->sendResponse(new ItemResource($this->itemRepository->find($item->id)), "عرض المنتج", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ItemRequest  $request
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
        if (!count($item->stocks)) {
            $this->deleteImages($item->itemImages()->pluck('img')->toArray(), 'items');
            $item->itemImages()->delete($item->id);
            if ($this->itemRepository->delete($item->id)) return $this->sendResponse("", "تم حذف المنتج", 200);
        }
        return $this->sendError("لا يمكن حذف منتجا له رصيد", [], 200);
    }

    /**
     * restore single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        return $this->sendResponse($this->itemRepository->restore($id), "", 200);
    }

    /**
     * restore all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function restoreAll()
    {
        return $this->sendResponse($this->itemRepository->restoreAll(), "", 200);
    }

    /**
     * force delete single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        return $this->sendResponse($this->itemRepository->forceDelete($id), "", 200);
    }

    /**
     * force delete all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function forceDeleteAll()
    {
        return $this->sendResponse($this->itemRepository->forceDeleteAll(), "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function toggleUpdate($id, $booleanName)
    {
        $item = $this->itemRepository->toggleUpdate($id, $booleanName);
        return $this->sendResponse($item[$booleanName], $booleanName. ' ' .$item[$booleanName] , 202);
    }
}
