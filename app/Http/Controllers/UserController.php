<?php

namespace App\Http\Controllers;


use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\FolderMiniCollection;
use App\Http\Resources\GroupMiniCollection;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use App\Traits\AuthTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class UserController extends Controller
{
    public function search(Request $request)
    {
        if (Str::length($request->q) < 3)
            return api_response(null, false, 500, 'Enter 3 min char');
        $results = Search::add(User::class, ['email', 'mobile'])
            ->search($request->q);
        return api_response($results);
    }

    public function folders()
    {
        return api_response(new FolderMiniCollection(Auth::user()->folders));
    }

    public function groups()
    {
        $group = Group::find(GroupUser::where('user_id', Auth::id())->pluck('group_id'));
        return api_response(new GroupMiniCollection($group));
    }

    public function update(UpdateUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user()->update([
                'name' => $request->user_name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'institute_name' => $request->institute_name
            ]);

            DB::commit();

            return api_response(Auth::user(), true, 200, 'User Updated');

        }
        catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed'
        ]);

        $user = $request->user();

        if (Hash::check($request->current_password, $user->password) && $user){
            if (Hash::check($request->password, $user->password)) {
                return api_response(null, false, 500, 'Password matched with your old password, choose a different password!');
            }
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            $request->user()->currentAccessToken()->delete();

            return api_response(null, true, 200, 'Password changed, Login again!');

        }

        return api_response(null, false, 500, 'Current password do not match with account password!');
    }

    public function view()
    {
//        dd(Auth::user());

        if (Auth::check())
            $data = [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'mobile' => Auth::user()->mobile,
                'institute_name' => @Auth::user()->institute_name,
                'user_type' => Auth::user()->user_type,
                'subscription_start' => @Auth::user()->subscription->subscription_start,
                'subscription_ends' => @Auth::user()->subscription->subscription_end,
                'subscription_plan' => @Auth::user()->subscription->plan->name,
                'total_groups' => Auth::user()->groupMember()->count()
            ];
//        dd($data);
            return api_response($data, true, 200, 'User Details Fetched !');

        return api_response('User is not available', false, 404, 'Failed!');

    }

}
