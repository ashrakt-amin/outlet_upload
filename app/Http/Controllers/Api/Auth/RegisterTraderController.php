<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Trader;
use App\Http\Controllers\Controller;
use App\Http\Requests\TraderRequest;
use App\Http\Resources\TraderResource;
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
        $user = new Trader();
        $user->fill($request->input());

        if ($request->has('img')) {
            if ($request->file('img') != null) {
                $img = $request->file('img');
                $user->img = $this->setImage($img, 'traders', 450, 450);
            }
        }

        $user->password = bcrypt($request->password);
        $user->code = uniqueRandomCode('traders');
        if ($user->save()) {
            $success['data']      =  new TraderResource($user);
            return $this->sendResponse($success, 'تم التسجيل بنجاح.');
        }
    }

}
