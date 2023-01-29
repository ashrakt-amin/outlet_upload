<?php

namespace App\Http\Controllers\Api;

use App\Models\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class ItemImageController extends Controller
{
    use TraitImageProccessingTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('img')) {
            foreach ($request->file('img') as $img) {
                $image            = new ItemImage();
                $image->item_id   = $request->item_id;
                $image->img       = $this->setImage($img, 'items', 450, 450);
                $image->save();
            }
        }
        if ($image->save()) {
            return response()->json([
                "success" => true,
                "message" => "تم اضافة الصورة",
                "data"    => ($image)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل اضافة الصورة",
            ], 422);
        }
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
        return DB::transaction(function() use($itemImage){
            $this->deleteImage(ItemImage::IMAGE_PATH, $itemImage->img);
            $itemImage->delete();
            return response()->json([
                "success" => true,
                "message" => "تم حذف الصورة",
            ], 200);
        });


        // try {
        //     DB::beginTransaction();
        //     $this->deleteImage(ItemImage::IMAGE_PATH, $itemImage->img);
        //     $itemImage->delete();
        //     return response()->json([
        //         "success" => true,
        //         "message" => "تم حذف الصورة",
        //     ], 200);
        //     DB::commit();
        // } catch(\Exception $e) {
        //     DB::rollback();
        //     throw $e;
        //     return response()->json([
        //         "success" => false,
        //         "message" => "فشل حذف الصورة",
        //     ], 422);
        // }
    }
}
