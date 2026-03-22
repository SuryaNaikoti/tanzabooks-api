<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFolderRequest;
use App\Http\Resources\FolderCollection;
use App\Http\Resources\FolderMiniCollection;
use App\Http\Resources\GroupUserMiniCollection;
use App\Http\Resources\TanzabookMiniCollection;
use App\Models\Folder;
use App\Models\Tanzabook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FoldersController extends Controller
{
    public function __construct(
        Folder $folder
    )
    {
        $this->folder = $folder;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(StoreFolderRequest $request)
    {
        try {
            DB::beginTransaction();
            $folder = $this->folder->create([
                'name' => $request->name,
                'user_id' => Auth::id()
            ]);
            DB::commit();
            return api_response($folder);
        }
        catch(\Throwable $exception){
            return api_error($exception);
        }

    }

    public function show(Folder $folder)
    {
//        dd($folder->tanzabooks);
        if (!$folder->user_id == Auth::id())
            return api_error(null, 'User do not own this folder!!');

//        dd($folder);
        $data['id'] = $folder->id;
        $data['name'] = $folder->name;
        $data['user_id'] = $folder->user_id;
        $data['tanzabooks'] = new TanzabookMiniCollection($folder->tanzabooks);

        return api_response($data);
    }

    public function edit(Folder $folder)
    {
        //
    }

    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            $folder->update([
                'name' => $request->name
            ]);
            DB::commit();
            return api_response($folder, true, 200, 'Folder Updated!');
        }
        catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);
        }
    }

    public function destroy(Folder $folder)
    {
        if ($folder->user_id !== Auth::id())
            return api_response(null, false, 403, "Unauthorized to Delete this folder!");

        try {
            DB::beginTransaction();
            if ($folder->tanzabooks->count())
                $folder->tanzabooks()->delete();
            $folder->delete();
            DB::commit();
            return api_response(null, true, 204, 'Folder Deleted!');

        } catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);
        }




    }
}
