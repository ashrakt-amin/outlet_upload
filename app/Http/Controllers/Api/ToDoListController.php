<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ToDoList;
use Illuminate\Http\Request;

class ToDoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $toDoLists = ToDoList::all();
        return response()->json([
            'data' => $toDoLists
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
        $toDoList = ToDoList::create($request->all());
        if ($toDoList) {
            return response()->json([
                "success" => true,
                "message" => "تم التسجيل",
                "data"    => $toDoList
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل التسجيل",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ToDoList  $toDoList
     * @return \Illuminate\Http\Response
     */
    public function show(ToDoList $toDoList)
    {
        return response()->json([
            "success" => true,
            "data"    => $toDoList
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ToDoList  $toDoList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ToDoList $toDoList)
    {
        if ($toDoList->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم التعديل",
                "data"    => $toDoList
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل التعديل",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ToDoList  $toDoList
     * @return \Illuminate\Http\Response
     */
    public function destroy(ToDoList $toDoList)
    {
        if ($toDoList->delete()) {
            return response()->json([
                "success" => true,
                "message" => "تم الحذف ",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل الحذف",
            ], 422);
        }
    }
}
