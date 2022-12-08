<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\DB;

trait AuthGuardTrait {
    /**
     *  get remembertoken name
     */
    public function getTokenRow() {
        [$id, $user_token] = explode('|', request()->bearerToken(), 2);
        $token_data = DB::table('personal_access_tokens')->where(['token' => hash('sha256', $user_token)])->first();
         return $token_data ;
    }

    /**
     *  get remembertoken id
     */
    public function getTokenName($guard) {
        $token_data = $this->getTokenRow();
        if ($token_data->name == $guard) {
            return $guard;
        }
    }
}
