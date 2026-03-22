<?php

use App\Models\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

function api_response($data = null, $status = true, $status_code = 200, $message = 'Success', $errors = null, $type = null)
{
//    $data ? gettype($data) == 'array' ? $data : $data->toArray() : null;

    return response()->json([
        "data" => $data,
        'success' => $status,
        "status_code" => $status_code,
        "message" => $message,
        "errors" => $errors ?? [],
    ], $status_code)
        ->withHeaders(['Content-Type' => "application/json"]);
}

if (!function_exists('api_asset')) {
    function api_asset($id)
    {
        if (($asset = Upload::find($id)) != null) {
            return $path = Storage::disk(env('FILESYSTEM_DISK'))->url($asset->file_name);
        }
        return '';
    }
}

if (!function_exists('api_error')) {
    function api_error($exception, $message = 'Something went wrong.', $statusCode = 500 )
    {
        app('sentry')->captureException($exception);
        return api_response(null, false, $statusCode, $message, $exception->getMessage());

    }
}

if (!function_exists('isPlanActive')) {
    function isPlanActive() {
        if (!Auth::check())
            return false;

        if (Auth::user()->subscription){
            if (Auth::user()->subscription->subscription_end->isFuture()) {
                return true;
            }
        }

        return false;
    }
}


