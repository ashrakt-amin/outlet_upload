<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Trader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TraderResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;


class RegisterTraderController extends Controller
{
    use TraitsAuthGuardTrait;
    use TraitImageProccessingTrait;
    use TraitResponseTrait;
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'            => 'unique:traders,phone|regex:/^(01)[0-9]{9}$/',
            'email'            => 'unique:traders,email',
            'password'         => 'required:traders,email',
            'f_name'           => 'required',
        ], [
            'email.unique'     => 'البريد الالكتروني مسجل من قبل',
            'phone.unique'     => 'الهاتف مسجل من قبل',
            'phone.required'   => 'الهاتف مطلوب',
            'phone.regex'      => 'يرجى التاكد ان الهاتف صحيحا',
            'f_name.required'  => 'الاسم الاول مطلوب',
            'password.required'=> 'الرقم السري مطلوب',
        ]);
        // $trader = Trader::where(['phone'=>$request->input('phone')])->first();
        // if ($trader != null) {
        //     return response()->json([
        //         'message' => 'الهاتف مسجل من قبل',
        //     ], 422);
        // }
        // if ($request->input('code')) {
        //     $user = Trader::where(['code'=>$request->input('code')])->first();
        //     $age = $user->age;
        //     if ($user) {
        //         if ($user->phone == $request->input('phone')) {
                    if ($request->input('password') !== $request->input('confirm_password')) {
                        return response()->json([
                            'message' => 'الرقم السري غير مطابق',
                        ], 422);
                    } else {
                        $user = new Trader();
                        $user->fill($request->input());
                        if ($request->has('img')) {
                            if ($request->file('img') != null) {
                                $img = $request->file('img');
                                $user->img = $this->setImage($img, 'traders', 450, 450);
                            }
                        }

                        $user->password = bcrypt($request->password);
                        $user->code = randomCode();

                        // if ($request->age != null) {
                        //     $user->age = $request->age;
                        // } else {
                        //     $user->age = $age;
                        // }
                        // $input = $request->all();
                        // $input['password'] = bcrypt($input['password']);
                        // $user->password = $input['password'];
                        // $user->updatesa();
                        if ($user->save()) {
                            // $success['token']     =  $user->createToken('trader')->plainTextToken;
                            // $success['tokenName'] =  "trader";
                            $success['name']      =  new TraderResource($user);
                            return $this->sendResponse($success, 'تم التسجيل بنجاح.');
                        }
                    }
            //     } else {
            //         return response()->json([
            //             'message' => 'الهاتف غير صحيح',
            //         ], 422);
            //     }
            // } else {
            //     return response()->json([
            //         'message' => 'لا يوجد تاجر بهذا الكود',
            //     ], 422);
            // }
        // }
    }

}
