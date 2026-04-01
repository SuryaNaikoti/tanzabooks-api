<?php

function api_response($data = [], $message = 'Success', $status = 200) {
    return response()->json([
        'status' => true,
        'message' => $message,
        'data' => $data
    ], $status);
}

function api_error($message = 'Error', $status = 400) {
    return response()->json([
        'status' => false,
        'message' => $message
    ], $status);
}
