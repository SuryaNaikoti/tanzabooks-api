<?php

namespace App\Http\Controllers;

use App\Http\Resources\FolderMiniCollection;
use App\Http\Resources\TanzabookMiniCollection;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class DashBoardController extends Controller
{
    public function index()
    {
        $data['total_connects'] = 0;
        $data['total_tanzabooks'] = Auth::user()->tanzabooks->count();
        $data['total_groups'] = Auth::user()->groupMember->count();
        $data['folders'] = new FolderMiniCollection(Auth::user()->folders()->get());
        $data['shared_with_me'] = new TanzabookMiniCollection(Auth::user()->tanzabooks);

        return api_response($data);
    }
}
