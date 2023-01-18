<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\View;
use App\Models\Project;
use App\Models\ItemImage;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use Illuminate\Support\Facades\File;
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
    public function index($attributes)
    {
        return $this->sendResponse(ItemResource::collection($this->itemRepository->all($attributes)), "", 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function latest()
    {
        return $this->sendResponse(ItemResource::collection($this->itemRepository->latest()), "", 200);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        $item = $this->itemRepository->create($request->validated());
        return $this->sendResponse(new ItemResource($item), "تم تسجيل ,منتجا جديدا", 200);
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
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function itemOffers(Item $item)
    {
        return $this->sendResponse(new ItemResource($this->itemRepository->find($item->id)), "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, Item $item)
    {
        if ($this->getTokenId('user')) {
            $item = $this->itemRepository->edit($item->id, $request->validated());
            return $this->sendResponse(new ItemResource($item), "تم تعديل ال,حدة", 200);
        }
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
        return $this->sendResponse(new ItemResource($item), "تم تعديل ال,حدة", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        if (!count($item->items)) {
            if ($this->itemRepository->delete($item->id)) return $this->sendResponse("", "تم حذف الوحدة");
        }
        return $this->sendError("لا يمكن حذف مشروعا له فروع", [], 405);
    }










    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function offers()
    {
        $items = item::where('discount', '>', 0)->get();
        return response()->json([
            "data" => ItemResource::collection($items),
        ]);
    }

    /**
     * Get items of a project.
     *
     * @return Collection
     */
    public function offerItemsOfProject($project_id, $category_id)
    {
        return ItemResource::collection(Item::whereHas('category', function($q) use($project_id, $category_id) {
            $q->whereHas('projects', function($q) use($project_id) {
                $q->where('project_id', $project_id);
            })
            ->where('category_id', $category_id)
            ->where('discount', '>', 0)
            ->distinct('id');
        })->get());
    }

    /**
     * Get items of a project.
     *
     * @return Collection
     */
    public function offerItemsOfCategoriesOfProject($project_id, $category_id)
    {
        return ItemResource::collection(Item::where(['project_id' => $project_id, 'category_id' => $category_id])->where('discount', '>', 0)->paginate());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function streetOffers($id)
    {
        $items = Project::where(['id' => $id])
            ->with(['units' => function($query){
                $query->with(['items' => function($query){
                    $query->where('discount' , '>', 0);
                    }]);
                }])
            ->get();

        return response()->json([
            "data" => ($items),
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function approved(Request $request, Item $item)
    {
            $item->approved = $request->approved;
        if ($item->update()) {
            return response()->json([
                "success" => true,
                "message" => "تم التصريح للمنتج",
                "data" => ($item),
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل التصريح للمنتج ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Item $item)
    // {
    //     if ($item->stocks->count() == 0) {
    //         $itemImages = ItemImage::where(['item_id'=>$item->id])->get();
    //         if ($item->delete()) {
    //             foreach ($itemImages as $itemImage) {
    //                 $image_path = "assets/images/uploads/items/".$itemImage->img;  // Value is not URL but directory file path
    //                 if(File::exists($image_path)) {
    //                     File::delete($image_path);
    //                     $itemImage->delete();
    //                 }
    //             }
    //             if (count($item->itemImages) < 1) {
    //                 return response()->json([
    //                     "success" => true,
    //                     "message" => "تم حذف المنتج",
    //                 ], 200);
    //             };
    //         } else {
    //             return response()->json([
    //                 "success" => false,
    //                 "message" => "فشل حذف المنتج ",
    //             ], 422);
    //         }
    //     } else {
    //         return response()->json([
    //             "success" => false,
    //             "message" => "لا يمكن حذف منتجا تم اضافته للمخزن",
    //         ], 422);
    //     }
    // }
}
