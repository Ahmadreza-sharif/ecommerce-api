<?php

namespace App\Http\Controllers\api\v1\Auth;

use App\Http\Controllers\api\traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\auth\LoginRequest;
use App\Http\Requests\Api\V1\auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function login(LoginRequest $request)
    {
        # GET DATA AND VALIDATION
        # CHECK DATA
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->sendError('', __('general.auth.customer.login.fail'));
        }

        # CREATE TOKEN
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        # SEND RESPONSE
        return $this->sendSuccess(['token' => $token], __('general.auth.customer.login.success'));
    }

    public function verifyEmail()
    {

    }

    public function register(RegisterRequest $request)
    {
        # GET DATA AND VALIDATION
        # CREATE TOKEN
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        # SEND RESPONSE
        return $this->sendSuccess(['token' => $token], __('general.auth.customer.register.success'));
    }

    public function logout()
    {
        # DELETE TOKEN
        auth('sanctum')->user()->tokens()->delete();

        # SEND RESPONSE
        return $this->sendSuccess('', __('general.auth.customer.logout'));
    }

    public function verify()
    {
        $user = \App\Models\User::find(1);
        $code = rand(1000,9999);
        $user->notify(new \App\Notifications\OtpNotification($code));

        return $this->sendSuccess([],'verification code send successfully');
    }
}
