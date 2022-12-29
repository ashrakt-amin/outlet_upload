<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Trader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TraderRequest;
use App\Http\Resources\TraderResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
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
    public function register(TraderRequest $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'phone'            => 'unique:traders,phone|regex:/^(01)[0-9]{9}$/',
        //     'email'            => 'unique:traders,email',
        //     'password'         => 'required:traders,email',
        //     'f_name'           => 'required',
        // ], [
        //     'email.unique'     => 'البريد الالكتروني مسجل من قبل',
        //     'phone.unique'     => 'الهاتف مسجل من قبل',
        //     'phone.required'   => 'الهاتف مطلوب',
        //     'phone.regex'      => 'يرجى التاكد ان الهاتف صحيحا',
        //     'f_name.required'  => 'الاسم الاول مطلوب',
        //     'password.required'=> 'الرقم السري مطلوب',
        // ]);

        // if($validator->fails()){
        //     return $this->sendError('Validation Error.', $validator->errors());
        // }

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
            if ($user->save()) {
                $success['data']      =  new TraderResource($user);
                return $this->sendResponse($success, 'تم التسجيل بنجاح.');
            }
        }
    }

}
