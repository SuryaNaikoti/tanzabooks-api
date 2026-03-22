<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Resources\TanzabookMiniCollection;
use App\Http\Resources\UserMiniCollection;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupsController extends Controller
{
    public function __construct(
        Group $group,
        GroupUser $groupUser
    )
    {
        $this->group = $group;
        $this->groupUser = $groupUser;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(StoreGroupRequest $request)
    {
        try {
            DB::beginTransaction();
            //creating group
            $group = $this->group->create([
                'name' => $request->name,
                'user_id' => Auth::id()
            ]);

            //creating user as group member
            $this->groupUser->create([
                'group_id' => $group->id,
                'user_id' => Auth::id()
            ]);
            DB::commit();
            return api_response($group);
        }
        catch(\Throwable $exception){
            return api_error($exception);
        }
    }

    public function show(Group $group)
    {
        $data = [
            'name' => $group->name,
            'created_by' => $group->id,
            'createdAt' => $group->created_at->format("d-m-Y")
        ];

        $data['members'] = new UserMiniCollection($group->group_users);
        $data['tanzabooks'] = new TanzabookMiniCollection($group->tanzabooks);

        return api_response($data);

    }

    public function edit(Group $group)
    {
        //
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            $group->update([
                'name' => $request->name
            ]);
            DB::commit();
            return api_response($group, true, 200, 'Group Updated!');
        }
        catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);
        }
    }

    public function destroy(Group $group)
    {
        if ($group->user_id !== Auth::id())
            return api_response(null, false, 403, "Unauthorized to Delete this folder!");

        try {
            DB::beginTransaction();
            if ($group->group_users->count())
                $group->group_users()->delete();
            $group->delete();
            DB::commit();
            return api_response(null, true, 204, 'Group Deleted!');

        } catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);
        }
    }
}
