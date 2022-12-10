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

                $image_path = storage_path('app/imagesFb');
                $image->move($image_path, $filename);

                $itemImage = new ItemImage();
                $itemImage->item_id = $item;
                $itemImage->img     = $filename;
                $itemImage->save();

                $img = Image::make(storage_path('app/imagesFb').$filename)->fit(200, 200)->save();
                // $img->resize(300, 300, function($constraint) {
                //     $constraint->aspectRatio();
                // });
                // $img->save(storage_path('app/imagesFb').'/'.$filename);
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
        $image_path = "assets/images/uploads/items/".$itemImage->img;  // Value is not URL but directory file path
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
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
