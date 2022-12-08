<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;

class ItemController extends Controller
{

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
    public function index()
    {
        $items = Item::all();
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
        $items = Item::latest()->take(10)->get();
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
        $items = Item::inRandomOrder()->limit(5)->get();
        return response()->json([
            "data" => ItemResource::collection($items),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        if ($item->save()) {
            $itemImage = new ItemImageController();
            $itemImage->storei($request, $item->id);
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
        $item = Item::where(['id'=>$item->id])->with('stocks')->first();
        $guard = $item->getTokenName('client');
        if ($guard || (request()->bearerToken() == null)) {
            $view = View::where(['item_id'=>$item->id, 'client_id'=>$item->getTokenId('client')])->first();
            if (!$view) {
                $view = new View();
                $view->item_id    = $item->id;
                $view->client_id  = auth()->guard()->id();
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
    public function update(Request $request, Item $item)
    {
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
        if ($item->colorSizeStocks->count() == 0) {
            if ($item->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف المنتج",
                ], 200);
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
