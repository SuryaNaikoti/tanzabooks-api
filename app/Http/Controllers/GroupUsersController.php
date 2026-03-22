<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupUsersRequest;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class GroupUsersController extends Controller
{
    public function __construct(
        User $user,
        GroupUser $groupUser,
        Group $group
    )
    {
        $this->user = $user;
        $this->groupuser = $groupUser;
        $this->group = $group;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(StoreGroupUsersRequest $request)
    {
        if (Group::where('id', $request->group_id)){
            $users = collect($request->invite_user)
            ->map(fn(array $user) : array => Arr::add($user, 'group_id', $request->group_id));

            try {
                DB::beginTransaction();
                foreach ($users as $user){
                    if (!$this->user->findOrFail($user['user_id']))
                        api_response(null, false, 404, 'Given user does not match!!');
                    GroupUser::firstOrCreate($user);

                }
                DB::commit();
                return api_response(null, true, 200, 'Group Members Updated!!');
            }
            catch (\Throwable $exception){
                DB::rollBack();
                return api_error($exception);
            }
        }
    }

    public function show(GroupUser $groupUser)
    {
        //
    }

    public function edit(GroupUser $groupUser)
    {
        //
    }

    public function update(Request $request, GroupUser $groupUser)
    {
        //
    }

    public function destroy(GroupUser $groupUser)
    {
        //
    }
}
