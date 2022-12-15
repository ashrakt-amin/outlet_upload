<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LevelImage;
use Illuminate\Http\Request;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class LevelImageController extends Controller
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
            foreach ($request->file('img') as $img) {
                $originalFilename = $this->setImage($img, $request->level_id, 'levels/lg');
                $filename = $this->aspectForResize($img, $request->level_id, 450, 450, 'levels/sm');
                $image = new LevelImage();
                $image->level_id = $request->level_id;
                $image->img     = $filename;
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
     * @param  \App\Models\LevelImage  $levelImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LevelImage $levelImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LevelImage  $levelImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(LevelImage $levelImage)
    {
        $lg_image_path = "levels/lg/".$levelImage->img;  // Value is not URL but directory file path
        $sm_image_path = "levels/sm/".$levelImage->img;  // Value is not URL but directory file path

        $this->deleteImage($lg_image_path);
        $this->deleteImage($sm_image_path);
        if ($levelImage->delete()) {
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
