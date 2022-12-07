<?php

namespace App\Http\Controllers\Api;
use App\Models\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\SubCategory;

class CategoryController extends Controller
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
        $categories = Category::all();
        $categories = Category::where('category_id', '<', 1)->get();
        return response()->json([
                'data' => CategoryResource::collection($categories)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category();
        $category->fill($request->input());
        if ($category->category_id == null)  {
            $category->category_id = 0;
        }
        $category->save();
        // $category = Category::create($request->all());
        if ($category) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل تصنيفا جديدا",
                "data" => new CategoryResource($category)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل التصنيف",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json([
            'data' => new CategoryResource($category),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if ($category->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل التصنيف",
                "data" => new CategoryResource($category)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل التصنيف",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->subCategories->count() == 0 ) {
            if ($category->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف التصنيف",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف التصنيف",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "لا يمكن حذف قسما يحتوي على اقسام فرعية",
            ], 422);
        }
    }
}
