<?php

namespace App\Traits;


use App\Models\Upload;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\ClassString;

/**
 *
 */
trait FileTrait
{
    protected function uploadFile($file, string $fileable_type, int $fileable_id)
    {
        $type = array(
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
            "mp4" => "video",
            "mpg" => "video",
            "mpeg" => "video",
            "webm" => "video",
            "ogg" => "video",
            "avi" => "video",
            "mov" => "video",
            "flv" => "video",
            "swf" => "video",
            "mkv" => "video",
            "wmv" => "video",
            "wma" => "audio",
            "aac" => "audio",
            "wav" => "audio",
            "mp3" => "audio",
            "zip" => "archive",
            "rar" => "archive",
            "7z" => "archive",
            "doc" => "document",
            "txt" => "document",
            "docx" => "document",
            "pdf" => "document",
            "csv" => "document",
            "xml" => "document",
            "ods" => "document",
            "xlr" => "document",
            "xls" => "document",
            "xlsx" => "document"
        );

        $data['file_original_name'] = null;

        $arr = explode('.', $file->getClientOriginalName());

        for ($i = 0; $i < count($arr) - 1; $i++) {
            if ($i == 0) {
                $data['file_original_name'] .= $arr[$i];
            } else {
                $data['file_original_name'] .= "." . $arr[$i];
            }
        }

        $uploadedFile = $file;
        $filename = time().$uploadedFile->getClientOriginalName();

        Storage::disk(env('FILESYSTEM_DISK'))->putFileAs(
            'public/uploads',
            $uploadedFile,
            $filename
        );

        $data['file_name'] = 'public/uploads/'.$filename;

//        dd($data['file_name']);

        if (auth()->user()) {

            $data['user_id'] = auth()->user()->id;
            $data['fileable_id'] = $fileable_id;
            $data['fileable_type'] = $fileable_type;
        }
        $data['extension'] = strtolower($file->getClientOriginalExtension());
        if (isset($type[$data['extension']])) {
            $data['type'] = $type[$data['extension']];
        } else {
            $data['type'] = "others";
        }
        $data['file_size'] = $file->getSize();

        try {
            DB::beginTransaction();
            $uploaded = Upload::create($data);
            DB::commit();
            return $uploaded;
        } catch (\Throwable $exception) {
            DB::rollBack();
            return api_response(null, false, 500, 'Error while uploading file!', $exception->getMessage());
        }

    }

    protected function get_uploaded_files($request)
    {
        $uploads = Upload::where([
            'fileable_id' => Auth::id(),
            'fileable_type' => User::class
        ]);

        if ($request->search != null) {
            $uploads->where('file_original_name', 'like', '%' . $request->search . '%');
        }
        if ($request->sort != null) {
            switch ($request->sort) {
                case 'newest':
                    $uploads->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $uploads->orderBy('created_at', 'asc');
                    break;
                case 'smallest':
                    $uploads->orderBy('file_size', 'asc');
                    break;
                case 'largest':
                    $uploads->orderBy('file_size', 'desc');
                    break;
                default:
                    $uploads->orderBy('created_at', 'desc');
                    break;
            }
        }
        return $uploads->orderBy('created_at', 'desc')->paginate(60)->appends(request()->query());
    }
}
