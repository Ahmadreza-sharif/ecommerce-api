<?php

namespace App\Http\Controllers\api\v1\Auth;

use App\Http\Controllers\api\traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\auth\LoginRequest;
use App\Http\Requests\Api\V1\auth\RegisterRequest;
use App\Http\Requests\Api\V1\Otp\UserEmailOtp;
use App\Http\Requests\Api\V1\Otp\UserEmailOtpRequest;
use App\Mail\OtpUser;
use App\Models\Role;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
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

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
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

    public function logout(): \Illuminate\Http\JsonResponse
    {
        # DELETE TOKEN
        auth('sanctum')->user()->tokens()->delete();

        # SEND RESPONSE
        return $this->sendSuccess('', __('general.auth.customer.logout'));
    }

    public function verify(): \Illuminate\Http\JsonResponse
    {
        $user = \App\Models\User::find(1);
        if (!empty($user->email_verified_at)) {
            return $this->sendSuccess([], 'your email has been verified');
        }
        $code = rand(10000, 99999);
        if (!empty($user->otp)) {
            $user->otp()->update([
                'try_count' => $user->otp->try_count + 1,
                'updated_at' => Carbon::now()->addMinute(5)->toDateTimeString()
            ]);
            $code = $user->otp->code;
        } else {
            \App\Models\OtpUser::create([
                'user_id' => $user->id,
                'code' => $code,
                'try_count' => '1'
            ]);
        }
        $user->notify(new \App\Notifications\OtpNotification($code));

        return $this->sendSuccess([], 'verification code send successfully');
    }

    public function confirm(UserEmailOtpRequest $request)
    {
        $now = Carbon::now()->toDateTimeString();
        $code = \App\Models\OtpUser::where('code', '=', $request->input('code'))
            ->where('try_count', '<', 6)
            ->where('updated_at', ">", $now)
            ->first();

        if (empty($code->user->email_verified_at)) {
            if (!empty($code)) {
                $code->user()->update([
                    'email_verified_at' => $now
                ]);

                return $this->sendSuccess([], 'verifying successfully done.');
            } else {
                return $this->sendError([], 'Invalid Code');
            }
        } else {
            return $this->sendError([],'your account is verified');
        }
    }
}
