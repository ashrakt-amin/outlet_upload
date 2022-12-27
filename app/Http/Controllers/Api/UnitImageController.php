<?php

namespace App\Http\Controllers\Api;

use App\Models\UnitImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;


class UnitImageController extends Controller
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
                $image             = new UnitImage();
                $image->unit_id    = $request->unit_id;
                $image->img        = $this->setImage($img, 'units', 450, 450);
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
     * Display the specified resource.
     *
     * @param  \App\Models\UnitImage  $unitImage
     * @return \Illuminate\Http\Response
     */
    public function show(UnitImage $unitImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UnitImage  $unitImage
     * @return \Illuminate\Http\Response
     */
    public function edit(UnitImage $unitImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UnitImage  $unitImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UnitImage $unitImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UnitImage  $unitImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(UnitImage $unitImage)
    {
        return DB::transaction(function() use($unitImage){
            $this->deleteImage(UnitImage::IMAGE_PATH, $unitImage->img);
            $unitImage->delete();
            return response()->json([
                "success" => true,
                "message" => "تم حذف الصورة",
            ], 200);
        });
    }
}
