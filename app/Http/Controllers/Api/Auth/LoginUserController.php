<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class LoginUserController extends Controller
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
        $users = User::all();
        if (count($users) < 1 ) {
            $user = new User();
            $user->f_name = "Ahmad";
            $user->m_name = "Ahmad";
            $user->l_name = "Ahmad";
            $user->phone = "01119657171";
            $user->password = bcrypt("12345678");
            $user->save();
        }

        if(Auth::attempt(['phone' => $request->phone, 'password' => $request->password])){
            $user = Auth::user();
            $success['token']     =  $user->createToken('user')->plainTextToken;
            $success['tokenName'] =  "user";
            $success['name']      =  $user;
            return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح.', 200);
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'بيانات غير صحيحة'], 401);
        }
    }
}
