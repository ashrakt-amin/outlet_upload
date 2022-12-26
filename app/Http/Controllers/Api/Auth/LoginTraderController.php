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
        if (Auth::guard('trader')->attempt([
            'phone' => $request->phone,
            'password' => $request->password
            ])) {
                $user = Trader::where(['phone' => $request->phone])->first();
                if ($user->approved == true) {
                    $success['token'] =  $user->createToken('trader')->plainTextToken;
                    $success['tokenName'] =  "trader";
                    $success['name']      =  $user;
                    return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح.');
                } elseif ($user->approved == false) {
                    if ($request->code == $user->code) {
                        $user->approved = 1;
                        $user->update();
                        $success['token'] =  $user->createToken('trader')->plainTextToken;
                        $success['tokenName'] =  "trader";
                        $success['name']      =  $user;
                        return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح.');
                    } else {
                        return $this->sendError('مصادقة غير مكتملة', ['error' => 'يرجى ادخال كود صحيح']);
                    }
                }
        }
        else{
            return $this->sendError('مصادقة غير مكتملة', ['error' => 'بيانات غير صحيحة']);
        }
    }
}
