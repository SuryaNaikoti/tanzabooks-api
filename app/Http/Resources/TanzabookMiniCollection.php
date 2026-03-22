<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Tanzabook */
class TanzabookMiniCollection extends ResourceCollection
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
                    'folder_id' => $data->folder_id,
                    'name' => $data->name,
                    'status' => $data->status,
                    'createdAt' => $data->created_at->format("d-m-Y")

                ];
            }),
        ];
    }
}
