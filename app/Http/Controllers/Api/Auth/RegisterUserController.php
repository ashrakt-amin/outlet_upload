<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class RegisterUserController extends Controller
{
    use TraitImageProccessingTrait;
    use TraitResponseTrait;
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(UserRequest $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        if (Auth::check()) {
            $user = User::create($input);
            $success['token'] =  $user->createToken('user')->plainTextToken;
            $success['tokenName'] =  DB::table('personal_access_tokens')->orderBy('id', 'DESC')->select('name')->where(['tokenable_id'=>$user->id])->first();
            $success['name'] =  $user;
            return $this->sendResponse($success, 'تم التسجيل بنجاح.');
        } else {
            return response()->json([
                'message' => "unauthenticated",
            ], 422);
        }
    }

}
