<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Trader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class LoginTraderController extends Controller
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
        if (Auth::guard('trader')->attempt(['phone' => $request->phone, 'password' => $request->password, ])) {
            $user = auth()->guard('trader')->user();
            if ($request->code != null) {
                $user = Trader::where(['code'=>$request->code, 'phone' => $request->phone])->first();
                $user->approved = 1;
                $user->update();
                $success['token'] =  $user->createToken('trader')->plainTextToken;
                $success['tokenName'] =  "trader";
                $success['name']      =  $user;
                return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح.');
            } elseif ($user->approved == true) {
                $success['token'] =  $user->createToken('trader')->plainTextToken;
                $success['tokenName'] =  "trader";
                $success['name']      =  $user;
                return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح.');
            } else {
                $success['name']      =  $user;
                return $this->sendResponse($success, 'يرجى ادخال كود تفعيل صحيح');
            }
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'بيانات غير صحيحة']);
        }
    }
}
