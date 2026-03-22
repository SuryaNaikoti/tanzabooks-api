<?php

namespace App\Http\Controllers;

use App\Models\Annotation;
use App\Models\Discussion;
use App\Models\Tanzabook;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscussionsController extends Controller
{

    use FileTrait;

    public function __construct(
        Annotation $annotation,
        Discussion $discussion,
        Tanzabook  $tanzabook,
    )
    {
        $this->annotation = $annotation;
        $this->discussion = $discussion;
        $this->tanzabook = $tanzabook;
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
//                dd($request->all());
//        dd(auth()->user()->getMorphClass());

//        if ($request->discussable_type == 'lesson'){
//            $lesson = $this->lesson->find($request->discussable_id)->with('file')->get();
//        }

        $tanzabook = $this->tanzabook->findOrFail($request->tanzabook);

//        dd($tanzabook);

        $data['discussable_id'] = $tanzabook->id;
        $data['discussable_type'] = $tanzabook->getMorphClass();
        $data['commentable_id'] = auth()->user()->id;
        $data['commentable_type'] = auth()->user()->getMorphClass();
        $data['comment'] = $request->comment;

        $discussion = $this->discussion->create($data);

//        dd($discussion);
        if ($request->has('file')) {
            $upload = $this->uploadFile($request->file, Discussion::class, $discussion->id);
            $discussion->update(['file_id' => $upload->id]);
        }

        $response = [
            'id' => $discussion->id,
            'comment' => $discussion->comment,
            'user_id' => $discussion->commentable_id,
            'username' => $discussion->commentable->name,
            'createdAt' => $discussion->created_at->format('D-M-Y'),
            'uuid' => $request->uuid ?? 0
        ];


        return api_response($response);
    }


    public function storeAnnotationComment(Request $request)
    {
//        dd($request);
        $annotation = $this->annotation->findOrFail($request->anotation_id);

//        dd($annotation);

        $data['discussable_id'] = $annotation->id;
        $data['discussable_type'] = $annotation->getMorphClass();
        $data['commentable_id'] = auth()->user()->id;
        $data['commentable_type'] = auth()->user()->getMorphClass();
        $data['comment'] = $request->comment;

        $discussion = $this->discussion->create($data);

        if ($request->has('file')) {
            $upload = $this->uploadFile($request->file, Discussion::class, $discussion->id);
            $discussion->update(['file_id' => $upload->id]);
        }


        return api_response($discussion);
    }

    public function show(Discussion $discussion)
    {
        //
    }

    public function update(Request $request, Discussion $discussion)
    {
        //
    }

    public function destroy(Discussion $discussion)
    {
        try {
            DB::beginTransaction();
            $discussion->delete();
            DB::commit();
            return api_response(null, true, 204, 'Comment/Discussion deleted!');
        } catch (\Throwable $exception) {
            DB::rollBack();
            return api_error($exception);
        }
    }
}
