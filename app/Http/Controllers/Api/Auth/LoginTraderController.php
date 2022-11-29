<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Client;

use App\Models\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\BaseController as BaseController;

class LoginTraderController extends BaseController
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::guard('trader')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $user = auth()->guard('trader')->user();
            $success['token'] =  $user->createToken('trader')->plainTextToken;
            $success['tokenName'] =  "trader";
            $success['name']      =  $user;
            return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'بيانات غير صحيحة']);
        }
    }
}
