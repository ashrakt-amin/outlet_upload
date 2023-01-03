<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\TraderResource;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($request->input('password') !== $request->input('password_confirmation')) {
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
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //'password' => ['required', 'string', 'min:4', 'confirmed'],
            // NO PASSWORD CONFIRMATION
            'password' => ['required', 'string', 'min:4'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $this->validator->stopOnFirstFailure();
        $this->validator->passes(); // false
        $this->validator->errors()->all(); // returns ['age' => 'The age must be at least 31'].

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    protected function guard()
    {
        return Auth::guard();
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $authuser = auth()->user();
            return response()->json(['message' => 'Login successful'], 200);
        } else {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged Out'], 200);
    }
}
