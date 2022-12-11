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

class RegisterUserController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'            => 'unique:users,phone|regex:/^(01)[0-9]{9}$/',
            'email'            => 'unique:users,email',
            'password'         => 'required:users,email',
            'f_name'           => 'required',
        ], [
            'email.unique'     => 'البريد الالكتروني مسجل من قبل',
            'phone.unique'     => 'الهاتف مسجل من قبل',
            'phone.required'   => 'الهاتف مطلوب',
            'phone.regex'      => 'يرجى التاكد ان الهاتف صحيحا',
            'f_name.required'  => 'الاسم الاول مطلوب',
            'password.required'=> 'الرقم السري مطلوب',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($request->input('password') !== $request->input('confirm_password')) {
            return response()->json([
                'message' => 'الرقم السري غير مطابق',
            ], 422);
        }

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
