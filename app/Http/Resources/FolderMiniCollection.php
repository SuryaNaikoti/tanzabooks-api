<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

/** @see \App\Models\Folder */
class FolderMiniCollection extends ResourceCollection
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
                    'folder_name' => $data->name,
                    'created_by_id' => $data->user_id,
                    'total_tanzabooks' => $data->tanzabooks->count(),
                    'total_members' => 0,
                    'ownership' => $data->user_id == Auth::id() ? 'Owner' : 'Shared',
                    'createdAt' => $data->created_at->format("d-m-Y")
                ];
            }),
        ];
    }
}
