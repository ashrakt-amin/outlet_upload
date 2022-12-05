<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Client;
use App\Models\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController as BaseController;

class RegisterTraderController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if ($request->input('code')) {
            $user = Trader::where(['code'=>$request->input('code')])->first();
            if ($user) {
                if ($user->phone == $request->input('phone')) {
                    if ($request->input('password') !== $request->input('confirm_password')) {
                        return response()->json([
                            'message' => 'الرقم السري غير مطابق',
                        ], 422);
                    } else {
                        if ($request->hasFile('logo')) {
                            $file            = $request->file('logo');
                            $ext             = $file->getClientOriginalExtension();
                            $filename        = time().'.'.$ext;
                            $file->move('assets/images/uploads/traders/', $filename);
                            $user->logo = $filename;
                        }
                        $input = $request->all();
                        $input['password'] = bcrypt($input['password']);
                        $user->password = $input['password'];
                        $user->update();
                        $success['token']     =  $user->createToken('trader')->plainTextToken;
                        $success['tokenName'] =  "trader";
                        $success['name']      =  $user;
                        return $this->sendResponse($success, 'register successfully.');
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
