<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Trader;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;


class RegisterTraderController extends BaseController
{
    use TraitsAuthGuardTrait;
    use TraitImageProccessingTrait;
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if ($request->input('code')) {
            $user = Trader::where(['code'=>$request->input('code')])->first();
            $age = $user->age;
            if ($user) {
                if ($user->phone == $request->input('phone')) {
                    if ($request->input('password') !== $request->input('confirm_password')) {
                        return response()->json([
                            'message' => 'الرقم السري غير مطابق',
                        ], 422);
                    } else {
                        $user->fill($request->input());
                        if ($request->has('img')) {
                            $img = $request->file('img');
                            $user->img = $this->setImage($img, 'traders', 450, 450);
                        }

                        $user->password = bcrypt($request->password);

                        if ($request->age != null) {
                            $user->age = $request->age;
                        } else {
                            $user->age = $age;
                        }
                        $input = $request->all();
                        $input['password'] = bcrypt($input['password']);
                        $user->password = $input['password'];
                        $user->update();
                        $success['token']     =  $user->createToken('trader')->plainTextToken;
                        $success['tokenName'] =  "trader";
                        $success['name']      =  $user;
                        if ($user->update()) {
                            return $this->sendResponse($success, 'تم التسجيل بنجاح.');
                        }
                    }
                } else {
                    return response()->json([
                        'message' => 'الهاتف غير صحيح',
                    ], 422);
                }
            } else {
                return response()->json([
                    'message' => 'لا يوجد تاجر بهذا الكود',
                ], 422);
            }
        }
    }

}
