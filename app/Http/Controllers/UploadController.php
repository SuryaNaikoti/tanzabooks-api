<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\User;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    use FileTrait;
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'type' => 'required|string'
        ]);

        $uplaod = $this->uploadFile($request->file, User::class, Auth::id());

        return api_response($uplaod, true, 201, 'File Uploaded!');

    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
