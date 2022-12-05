<?php

namespace App\Http\Controllers\Api;

use App\Models\Type;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;

class GroupController extends Controller
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
        $groups = Group::with('subCategory')->get();
        return response()->json([
                'data' => GroupResource::collection($groups)
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
        $group = Group::create($request->all());
        if ($group) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل مجموعة جديدا",
                "data" => new GroupResource($group)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل المجموعة ",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return response()->json([
            "success" => true,
            "data" => new GroupResource($group)
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        if ($group->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل المجموعة",
                "data" => new GroupResource($group)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل المجموعة ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        if ($group->types->count() == 0 ) {
            if ($group->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف المجموعة",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف المجموعة ",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "لا يمكن حذف مجموعة تحتوي على انواع",
            ], 422);
        }
    }
}
