<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
    * Logout function
    *
    * @return \Illuminate\Http\Response
    */
   public function logout(Request $request)
    {
        $logout = $request->user()->currentAccessToken()->delete(); // logout from this device
        // $logout = $request->user()->tokens()->delete(); //  logout from all devices
        // $logout = auth()->logout();
        if ($logout) {
            return response()->json([
                'message' => 'تم تسجيل الخروج',
            ], );
        } else {
            return response()->json([
                'message' => 'فشل تسجيل الخروج',
            ], );
        }
    }
}
