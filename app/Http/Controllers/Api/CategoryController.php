<?php

namespace App\Http\Controllers\Api;
use App\Models\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Repository\CategoryRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class CategoryController extends Controller
{
    use TraitResponseTrait;
    private $categoryRepository;
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        if(request()->bearerToken() != null) {
            return $this->middleware('auth:sanctum');
        };
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(CategoryResource::collection($this->categoryRepository->all()), "", 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = $this->categoryRepository->create($request->validated());
        return $this->sendResponse(new CategoryResource($category), "تم تسجيل تصنيفا جديدا", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->sendResponse(new CategoryResource($category), "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category = $this->categoryRepository->edit($category->id, $request->validated());
        return $this->sendResponse(new CategoryResource($category), "تم تعديل التصنيف");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->items->count() == 0 ) {
            if ($this->categoryRepository->delete($category->id)) return $this->sendResponse(new CategoryResource($category), "تم حذف التصنيف");
            $errorMessages = ['1', '2', '3', '4', '5', '6'];
            return $this->sendError("فشل حذف التصنيف", $errorMessages, 404);
        }
        return $this->sendError("لا يمكن حذف قسما يحتوي على اقسام فرعية", [], 405);
    }
}
