<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
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
        if ($request->hasFile('img')) {
            foreach ($request->file('img') as $img) {
                $originalFilename  = $this->setImage($img, $request->project_id, 'projects/lg');
                $filename          = $this->aspectForResize($img, $request->project_id, 450, 450, 'projects/sm');
                $image             = new ProjectImage();
                $image->project_id = $request->project_id;
                $image->img        = $filename;
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
        $lg_image_path = "projects/lg/".$projectImage->img;  // Value is not URL but directory file path
        $sm_image_path = "projects/sm/".$projectImage->img;  // Value is not URL but directory file path

        $this->deleteImage($lg_image_path);
        $this->deleteImage($sm_image_path);
        if ($projectImage->delete()) {
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
