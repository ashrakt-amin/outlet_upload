<?php

namespace App\Http\Controllers\Api;

use App\Models\ItemImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Resources\ItemImageResource;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class ItemImageController extends Controller
{
    use TraitImageProccessingTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itemImages = ItemImage::all();
        return response()->json([
                'data' => ItemImageResource::Collection($itemImages)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storei(Request $request ,$item)
    {
        if ($request->hasFile('img')) {
            foreach ($request->file('img') as $image) {
                    $name            = $image->getClientOriginalName();
                    $ext             = $image->getClientOriginalExtension();
                    $filename        = rand(10, 100000).time().'.'.$ext;
                    $image->move('assets/images/uploads/items/', $filename);

                    $itemImage = new ItemImage();
                    $itemImage->item_id = $item;
                    $itemImage->img     = $filename;
                    $itemImage->save();
                // $itemImage = new ItemImage();
                // $itemImage->item_id = $item;
                // $itemImage->img     = $this->aspectForResize($image, $item, 600, 450, 'items');
                // $itemImage->save();
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $itemImage = new ItemImage();
        $itemImage->fill($request->input());
        if ($request->hasFile('img')) {
            foreach ($request->file('img') as $image) {
                $name            = $image->getClientOriginalName();
                $ext             = $image->getClientOriginalExtension();
                $filename        = rand(10, 100000).time().'.'.$ext;
                $image->move('assets/images/uploads/items/', $filename);

                $itemImage = new ItemImage();
                $itemImage->item_id = $request->item_id;
                $itemImage->img     = $filename;
                $itemImage->save();
            }
        }
        if ($itemImage->save()) {
            return response()->json([
                "success" => true,
                "message" => "تم اضافة الصورة",
                "data"    => ($itemImage)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل اضافة الصورة",
            ], 422);
        }
    }

    public function store2(Request $request) {
        $data = $request->all();
        if ($request->hasfile('img')){
            $data['img'] = $this->aspectForResize($request->img, $request->item_id, 2000, 1800, 'items');
        }
        $create = ItemImage::create($data);
        return $create;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemImage  $itemImage
     * @return \Illuminate\Http\Response
     */
    public function show(ItemImage $itemImage)
    {
        $itemImages = ItemImage::where(['item_id'=>$itemImage->item_id])->get();
        return response()->json([
                'data' => ItemImageResource::Collection($itemImages)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemImage  $itemImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemImage $itemImage)
    {
        if ($request->file('img')) {
            $image_path = "assets/images/uploads/items/".$itemImage->img;  // Value is not URL but directory file path
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $file            = $request->file('img');
            $name            = $file->getClientOriginalName();
            $ext             = $file->getClientOriginalExtension();
            $filename        = time().'.'.$ext;
            $itemImage->img  = $filename;
        }
        if ($itemImage->update()) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الصورة",
                "data"    => ($itemImage)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل الصورة",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemImage  $itemImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemImage $itemImage)
    {
        $lg_image_path = "assets/images/uploads/items/lg/".$itemImage->img;  // Value is not URL but directory file path
        $sm_image_path = "assets/images/uploads/items/sm/".$itemImage->img;  // Value is not URL but directory file path

        $this->deleteImage($lg_image_path);
        $this->deleteImage($sm_image_path);
        dd($lg_image_path, $sm_image_path);
        if ($itemImage->delete()) {
            return response()->json([
                "success" => true,
                "message" => "تم حذف الصورة",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف الصورة",
            ], 422);
        }
    }
}
