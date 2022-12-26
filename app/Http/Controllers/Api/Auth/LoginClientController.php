<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class LoginClientController extends Controller
{
    use TraitImageProccessingTrait;
    use TraitResponseTrait;
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::guard('client')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $user = auth()->guard('client')->user();
            $success['token'] =  $user->createToken('client')->plainTextToken;
            $success['tokenName'] =  "client";
            $success['name']      =  $user;
            return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'بيانات غير صحيحة']);
        }
    }
}
