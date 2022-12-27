<?php

namespace App\Http\Controllers\Api;

use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class ProjectImageController extends Controller
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
                $image = new ProjectImage();
                $image->project_id = $request->project_id;
                $image->img = $this->setImage($img, 'projects', 450, 450);
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
     * @param  \App\Models\ProjectImage  $projectImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectImage $projectImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectImage  $projectImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectImage $projectImage)
    {
        return DB::transaction(function() use($projectImage){
            $this->deleteImage(ProjectImage::IMAGE_PATH, $projectImage->img);
            $projectImage->delete();
            return response()->json([
                "success" => true,
                "message" => "تم حذف الصورة",
            ], 200);
        });
    }
}
