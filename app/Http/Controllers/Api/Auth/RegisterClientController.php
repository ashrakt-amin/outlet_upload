<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class RegisterClientController extends Controller
{
    use TraitResponseTrait;
    use TraitImageProccessingTrait;
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(ClientRequest $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Client::create($input);
        $success['token'] =  $user->createToken('client')->plainTextToken;
        $success['tokenName'] =  DB::table('personal_access_tokens')->orderBy('id', 'DESC')->select('name', 'tokenable_id')->where(['tokenable_id'=>$user->id])->first();
        $success['name']  =  $user;
        return $this->sendResponse($success, 'تم التسجيل بنجاح.');
    }

}
