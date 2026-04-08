<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\VerifyMobileOtpRequest;
use App\Models\PasswordReset;
use App\Models\User;
use App\Traits\AuthTrait;
use App\Traits\FileTrait;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use AuthTrait, FileTrait, SmsTrait;

    public function __construct(
        User          $user,
        PasswordReset $passwordReset
    )
    {
        $this->user = $user;
        $this->passwordReset = $passwordReset;
    }

    public function index()
    {
        //
    }

    public function register(UserRegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $user_data = [
                'institute_name' => $request->institute_name,
                'name' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_type' => $request->user_type
            ];

            $user = $this->user->firstOrCreate($user_data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => $user
            ], 200);


        } catch (\Throwable $exception) {
            DB::rollBack();
            return api_error($exception);
        }
    }

    public function verifyOtp(VerifyMobileOtpRequest $request)
    {
        $verify = $this->verifyMobileOtp($request->otp, $request->mobile);

        return $verify;

    }

    public function login(UserLoginRequest $request)
    {
        try {
            DB::beginTransaction();

            if (Auth::attempt(['mobile' => $request->mobile, 'password' => $request->password])) {
                $user = Auth::user();
                $tokenResult = $user->createToken('User Access Token', ['access:user']);
                DB::commit();
                return $this->loginSuccess($tokenResult, $user);
            }
            return api_response(['user' => null], false, 401, 'Username or password does not match.');

        } catch (\Throwable $exception) {
            DB::rollback();
            app('sentry')->captureException($exception);
            return api_response(['user' => null], false, 501, 'Something went wrong.', $exception->getMessage());
        }
    }

    public function forgetPassword(Request $request)
    {

        $user = $this->user->where('mobile', $request->mobile)->firstOrFail();

        if ($user){
            $this->sendMobileOtp($user->mobile);
            return api_response('Otp send to mobile number +91' . $request->mobile);
        }

        return api_response(null, false, 404, 'Mobile number do not match any user');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $this->user->where('mobile', $request->mobile)->firstOr(function () {
            return null;
        });
        if ($user) {
            $passwordToken = $this->passwordReset->where('email', $user->email)->firstOr(function () {
                return null;
            });
        }

        if (@$passwordToken) {
            if (Hash::check($request->password, $user->password)) {
                return api_response('Password matched with your old password, choose a different password!', false);
            }
            try {
                DB::beginTransaction();
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
                $this->passwordReset->where('email', $user->email)->delete();

                DB::commit();
            } catch (\Throwable $exception) {
                DB::rollBack();
                return api_error($exception);
            }
            return api_response('Password Change Successfully!');
        }
        return api_response('User do not match or Token is expired!', false, 500);

    }

    public function logout()
    {
        Session::flush();
        Auth::user()->currentAccessToken()->delete();
        return api_response(null, true, 200, 'Logout Successfully');
    }
}
