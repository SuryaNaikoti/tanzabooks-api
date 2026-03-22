<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\User */
class UserMiniCollection extends ResourceCollection
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
                    'id' => $data->user->id,
                    'ame' => $data->user->name,
                    'email' => $data->user->email,
                    'mobile' => $data->user->mobile,
                    'member_since' => $data->user->created_at->format("d-m-Y")
                ];
            }),
        ];
    }
}
