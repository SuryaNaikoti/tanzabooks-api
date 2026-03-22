<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnotationRequest;
use App\Http\Resources\AnnotationCommentMiniCollection;
use App\Http\Resources\DiscussionMiniCollection;
use App\Models\Annotation;
use App\Models\Tanzabook;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnnotationsController extends Controller
{
    use FileTrait;

    public function __construct(Annotation $annotation)
    {
        $this->annotation = $annotation;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(StoreAnnotationRequest $request)
    {
//        dd($request->annotation_json['comment']['audio']);
//        return json_encode($request->position);
//        return json_decode($request->data, true);
        try {
            DB::beginTransaction();

            //creating annotation
            $annotation = $this->annotation->create([
                'tanzabook_id' => $request->tanzabook_id,
                'user_id' => Auth::id(),
                'data' => json_encode($request->annotation_json['position']),
                'comment' => $request->annotation_json['content']['text'],
                'file' => $request->annotation_json['comment']['audio']
            ]);

            DB::commit();

            $response['tanzabook_id'] = $annotation->tanzabook_id;
            $response['audio'] = api_asset($annotation->file);
            $response['comment'] = $annotation->comment;
            $response['annotation_json'] = json_decode($annotation->data);

            return api_response($response, true, 201, 'Annotation created!');

        }
        catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);
        }
    }

    public function show(Annotation $annotation)
    {
        //
    }

    public function edit(Annotation $annotation)
    {
        //
    }

    public function update(Request $request, Annotation $annotation)
    {
        //
    }

    public function destroy(Annotation $annotation)
    {
        try {
            DB::beginTransaction();
            $annotation->delete();
            DB::commit();
            return api_response(null, true, 204, 'Annotation deleted!');
        }
        catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);
        }
    }

    public function comments(Annotation $annotation)
    {
//        dd($annotation->comments);
        return new AnnotationCommentMiniCollection($annotation->comments);
    }
}
