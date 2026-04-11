<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveFolderRequest;
use App\Http\Requests\StoreTanzabookRequest;
use App\Http\Resources\AnnotaionCollection;
use App\Http\Resources\DiscussionMiniCollection;
use App\Http\Resources\FolderMiniCollection;
use App\Http\Resources\TanzabookMiniCollection;
use App\Models\Folder;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Tanzabook;
use App\Models\TanzabookUser;
use App\Models\User;
use App\Traits\FileTrait;
use App\Traits\SharedTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TanzabooksController extends Controller
{
    use FileTrait, SharedTrait;

    public function __construct(
        Tanzabook $tanzabook,
        TanzabookUser $tanzabookUser
    )
    {
        $this->tanzabook = $tanzabook;
        $this->tanzabookUser = $tanzabookUser;
//        dd('hello');
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Log::info($request->all());

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'name' => 'required|string',
                'file' => 'required|file',
                'folder_id' => 'nullable|integer',
                'type' => 'nullable|string|in:file,folder'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $type = $request->input('type', 'file');

            $path = $request->file('file')->store('uploads', 'public');

            $item = Tanzabook::create([
                'name' => $request->name,
                'folder_id' => $request->folder_id,
                'file_path' => $path,
                'type' => $type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => $item
            ], 200);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

    public function show(Tanzabook $tanzabook)
    {
//        if ($tanzabook->folder->user_id !== Auth::id() && empty($tanzabook->file_id))
//            return api_response(null, false, 419, 'Tanzabook Unauthorized or file does not exist');
        $data = [
            'id' => $tanzabook->id,
            'name' => $tanzabook->name,
            'file' => api_asset($tanzabook->file_id),
            'createdAt' => $tanzabook->created_at->format("d-m-Y")
        ];
        $data['annotations'] = new AnnotaionCollection($tanzabook->annotations);
        $data['discussion'] = Auth::user() ? new DiscussionMiniCollection($tanzabook->discussions) : null;

        return api_response($data);
    }

    public function edit(Tanzabook $tanzabook)
    {
        //
    }

    public function update(Request $request, Tanzabook $tanzabook)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            $tanzabook->update([
                'name' => $request->name
            ]);
            DB::commit();
            return api_response($tanzabook, true, 200, 'Tanzabook Updated!');
        }
        catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);
        }
    }

    public function destroy(Tanzabook $tanzabook)
    {
        try {
            DB::beginTransaction();
            $tanzabook->delete();
            DB::commit();
            return api_response(null, true, 200, 'Tanzabook Deleted !');
        }
        catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);
        }
    }



    public function share(Request $request)
    {
        if (Tanzabook::findOrFail($request->tanzabook_id) && $request->invite_user) {
            $users = collect($request->invite_user)
                ->map(fn(array $user): array => Arr::add($user,
                    'tanzabook_id', $request->tanzabook_id));

            try {
                DB::beginTransaction();

                foreach ($users as $user){
                    $this->tanzabookUser->firstOrCreate($user);
                }

                DB::commit();
                return api_response(null, true, 200, 'Tanzabook shared!!');
            }
            catch (\Throwable $exception){
                DB::rollBack();
                return api_error($exception);
            }
        }

        return api_error(null, 'Users not selected', 400);
    }

    public function move(MoveFolderRequest $request)
    {
        try {
            DB::beginTransaction();

            $tanzabook = $this->tanzabook->findOrFail($request->tanzabook_id);
            if ($tanzabook->folder_id == $request->folder_id)
                return api_response(null, false, 500, 'Already in the folder!');

            $tanzabook->update([
                'folder_id' => $request->folder_id
            ]);

            DB::commit();

            return api_response(null, true, 200, 'Folder Changed Successfully!');

        }
        catch (\Throwable $exception){
            DB::rollBack();
            return api_error($exception);

        }
    }

    public function sample_folder()
    {
        $user = User::find(116);
        return api_response(new FolderMiniCollection($user->folders));
    }

    public function sample_folder_show(Request $request)
    {
        $folder = Folder::with('tanzabooks')->find($request->folder_id)->tanzabooks;
        return api_response(new TanzabookMiniCollection($folder));
    }

    public function discussions(Tanzabook $tanzabook)
    {
        return new DiscussionMiniCollection($tanzabook->discussions);
    }

    public function sharedWithMe()
    {
//        dd("hello from method");
//        dd(Auth::user());
        $tbs = TanzabookUser::where('user_id', Auth::id())->pluck('tanzabook_id');
        $tbs = Tanzabook::find($tbs);
//        dd($tbs);
        return api_response(new TanzabookMiniCollection($tbs));
    }
}
