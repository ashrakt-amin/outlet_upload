<?php

namespace App\Http\Controllers\Api;
use App\Models\SubCategory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubCategoryResource;
use App\Models\Group;

class SubCategoryController extends Controller
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
        $subCategories = SubCategory::all();
        return response()->json([
            "data" => SubCategoryResource::collection($subCategories)
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
        $subCategory = SubCategory::create($request->all());
        if ($subCategory) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل تصنيفا فرعيا جديدا",
                "data" => new SubCategoryResource($subCategory)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل التصنيف الفرعي",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategory $subCategory)
    {
        // $subCategory = SubCategory::with(['groups'])->get();
        return response()->json([
            'data' => new SubCategoryResource($subCategory)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        if ($subCategory->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => " تم تعديل التصنيف الفرعي",
                "data" => new SubCategoryResource($subCategory)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل التصنيف الفرعي",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subCategory)
    {
        if ($subCategory->groups->count() == 0 ) {
            if ($subCategory->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف التصنيف الفرعي",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف التصنيف الفرعي",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "لا يمكن حذف قسما فرعيا يحتوي على مجموعات",
            ], 422);
        }
    }
}
