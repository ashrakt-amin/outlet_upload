<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdvertisementImage;
use Illuminate\Http\Request;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class AdvertisementImageController extends Controller
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
        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $originalFilename        = $this->setImage($img, $request->advertisement_id, 'advertisements/lg');
            $filename                = $this->aspectForResize($img, $request->advertisement_id, 450, 450, 'advertisements/sm');
            $image                   = new AdvertisementImage();
            $image->advertisement_id = $request->advertisement_id;
            $image->img              = $filename;
            $image->save();
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdvertisementImage  $advertisementImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvertisementImage $advertisementImage)
    {
        $lg_image_path = "advertisements/lg/".$advertisementImage->img;  // Value is not URL but directory file path
        $sm_image_path = "advertisements/sm/".$advertisementImage->img;  // Value is not URL but directory file path

        $this->deleteImage($lg_image_path);
        $this->deleteImage($sm_image_path);
        if ($advertisementImage->delete()) {
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
