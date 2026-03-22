<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Discussion */
class AnnotationCommentMiniCollection extends ResourceCollection
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
                    'comment' => $data->comment,
                    'user_id' => $data->commentable_id,
                    'username' => $data->commentable->name,
                    'createdAt' => $data->created_at->format('D-M-Y')
                ];
            }),
        ];
    }
}
