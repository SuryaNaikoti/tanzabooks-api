<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

/** @see \App\Models\Group */
class GroupMiniCollection extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data){
                return [
                    'id' => $data->id,
                    'group_name' => $data->name,
                    'created_by_id' => $data->user_id,
                    'total_tanzabooks' => $data->tanzabooks->count(),
                    'total_members' => $data->group_users->count(),
                    'ownership' => $data->user_id == Auth::id() ? 'Owner' : 'Shared',
                ];
            }),
        ];
    }
}
