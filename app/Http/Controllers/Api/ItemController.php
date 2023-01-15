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
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class ItemController extends Controller
{
    use TraitsAuthGuardTrait;
    use TraitImageProccessingTrait;
    public function __construct ()
    {
        $authorizationHeader = \request()->header('Authorization');
        if(request()->bearerToken() != null) {
            $this->middleware('auth:sanctum');
        };
        // if(isset($authorizationHeader)) {
        //     $this->middleware('auth:sanctum');
        // };
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Item::with(['unit'])->where(function($q) use($request){
            !$request->has('name') ?: $q->where('name', 'LIKE', "%{$request->name}%");
        })->get();

        return response()->json([
                'data' => ItemResource::collection($items)
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function latest()
    {
        $items = Item::with(['unit'])->latest()->take(10)->get();
        return response()->json([
            "data" => ItemResource::collection($items),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function random()
    {
        $items = Item::with(['unit'])->inRandomOrder()->limit(4)->get();
        return response()->json([
            "data" => ItemResource::collection($items),
        ]);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        dd('a');
        $itemExist = Item::where(['item_code'=>$request->item_code])->first();
        if ($itemExist) {
            return response()->json([
                "success" => true,
                "message" => "موجود من قبل",
                "data" => $itemExist->name
            ], 200);
        }
        $item = new Item();
        $item->fill($request->input());
        if ($request->buy_discount > 0) {
            $item->buy_price = $request->sale_price - ($request->sale_price * $request->buy_discount / 100);
        } else {
            $item->buy_price = $request->buy_price;
        }
        if ($item->save()) {
            if ($request->has('img')) {
                foreach ($request->file('img') as $img) {
                    $image = new ItemImage();
                    $image->item_id = $item->id;
                    $image->img = $this->setImage($img, 'items', 450, 450);
                    $image->save();
                }
            }
            return response()->json([
                "success"  => true,
                "message"  => "تم تسجيل منتجا جديدا",
                "data"     => new ItemResource($item),
                "approved" => $item->approved == 1 ? true : false,
      ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل المنتج ",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        $item = $item->load(['stocks', 'unit']);
        $user = $item->getTokenName('user');
        $trader = $item->getTokenName('trader');
        if (!$user && !$trader) {
            $view = View::where(['item_id'=>$item->id, 'client_id'=>$item->getTokenId('client')])->first();
            if (!$view) {
                $view = new View();
                $view->item_id    = $item->id;
                $view->client_id  = $item->getTokenId('client');
                $view->view_count = 1;
                $view->save();
            } elseif ($view) {
                $view->view_count = $view->view_count + 1;
                $view->update();
            }
        }
        return response()->json([
            "success" => true,
            "data"    => new ItemResource($item),
        ], 200);
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
            if ($item->update($request->all())) {
                return response()->json([
                    "success" => true,
                    "message" => "تم تعديل المنتج",
                    "data" => new ItemResource($item)
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل تعديل المنتج ",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "يرجى التسجيل كمستخدم",
            ], 422);
        }
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
    public function destroy(Item $item)
    {
        if ($item->stocks->count() == 0) {
            $itemImages = ItemImage::where(['item_id'=>$item->id])->get();
            if ($item->delete()) {
                foreach ($itemImages as $itemImage) {
                    $image_path = "assets/images/uploads/items/".$itemImage->img;  // Value is not URL but directory file path
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                        $itemImage->delete();
                    }
                }
                if (count($item->itemImages) < 1) {
                    return response()->json([
                        "success" => true,
                        "message" => "تم حذف المنتج",
                    ], 200);
                };
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف المنتج ",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "لا يمكن حذف منتجا تم اضافته للمخزن",
            ], 422);
        }
    }
}
