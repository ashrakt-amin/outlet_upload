<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;

class UserController extends Controller
{
    use TraitsAuthGuardTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->getTokenId('user')) {
            $users = User::all();
            return response()->json([
                'data' => ($users)
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if ($this->getTokenId('user')) {
            $user = User::where(['id'=>$this->getTokenId('user')])->first();
            return response()->json([
                'data' => ($user)
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($user->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل المستخدم",
                "data"    => ($user)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل المستخدم ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
