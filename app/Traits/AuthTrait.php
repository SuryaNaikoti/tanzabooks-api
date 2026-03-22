<?php
namespace App\Traits;
use Carbon\Carbon;

/**
 *
 */
trait AuthTrait
{
    protected $username ;
    protected $user_login = "email";
    protected $login_id = null;

    public function username($username)
    {
            $this->user_login = "email";
            $this->username = $username;
            $this->login_id = $username;

        return $this->user_login;
    }

    protected function loginSuccess($tokenResult, $user)
    {
        return response()->json([
            'result' => true,
            'message' => 'Successfully logged in',
            'access_token' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'institute_name' => @$user->institute_name,
                'user_type' => $user->user_type,
                'subscription_start' => @$user->subscription->subscription_start,
                'subscription_ends' => @$user->subscription->subscription_end,
                'subscription_plan' => @$user->subscription->plan->name
            ]
        ]);
    }

    protected function signupSuccess($user)
    {
        return response()->json([
            'result' => true,
            'message' => 'Successfully Registered!',
            'user' => [
                'id' => $user->id,
                'institute_name' => $user->institute_name,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'user_type' => $user->user_type
            ]
        ]);
    }
}
