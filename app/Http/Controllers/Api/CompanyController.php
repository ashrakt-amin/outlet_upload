<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyCollection;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Company::all();
        return response()->json([
                'data' => new CompanyCollection($items)
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
        $company = Company::create($request->all());
        if ($company) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل الشركة الموزعة",
                "data" => $company
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل الشركة الموزعة ",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return response()->json([
            "success" => true,
            "data" => new CompanyResource($company)
        ], 200);

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        if ($company->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الشركة الموزعة ",
                "data" => $company
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل الشركة الموزعة ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        if ($company->items->count() == 0) {
            if ($company->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف الشركة الموزعة ",
                    "data" => $company
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف الشركة الموزعة ",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف شركة موزعة لديها منتجات",
            ], 422);
        }
    }
}
