<?php

namespace App\Http\Controllers\Api;

use App\Models\LevelImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
        if ($request->has('img')) {
            foreach ($request->file('img') as $img) {
                $image = new LevelImage();
                $image->level_id = $request->level_id;
                $image->img = $this->setImage($img, 'levels', 450, 450);
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
        return DB::transaction(function() use($levelImage){
            $this->deleteImage(LevelImage::IMAGE_PATH, $levelImage->img);
            $levelImage->delete();
            return response()->json([
                "success" => true,
                "message" => "تم حذف الصورة",
            ], 204);
        });
    }
}
