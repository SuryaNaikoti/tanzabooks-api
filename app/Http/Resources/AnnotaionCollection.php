<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Annotation */
class AnnotaionCollection extends ResourceCollection
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
                    'tanzabook_id' => $data->tanzabook_id,
                    'user_id' => $data->user_id,
                    'audio' => api_asset($data->file),
                    'audio_url' => asset($data->file),
                    'comment' => $data->comment,
                    'position' => json_decode($data->data),
                    'createdAt' => $data->created_at->format('Y-m-d')

                ];
            }),
        ];
    }
}
