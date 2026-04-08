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

    public function resendOtp(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully (mock)'
        ], 200);
    }

    public function login(UserLoginRequest $request)
    {
        $user = User::where('mobile', $request->mobile)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username or password does not match'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => 'demo-token'
            ]
        ], 200);
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
        $user = User::where('mobile', $request->mobile)->first();

        if ($user) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully (mock)'
        ], 200);

    }

    public function logout()
    {
        Session::flush();
        Auth::user()->currentAccessToken()->delete();
        return api_response(null, true, 200, 'Logout Successfully');
    }
}
