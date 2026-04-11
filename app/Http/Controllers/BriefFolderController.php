<?php

namespace App\Http\Controllers;

use App\Models\BriefFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BriefFolderController extends Controller
{
    /**
     * Store a newly created brief folder in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('BriefFolder creation attempt', ['data' => $request->all(), 'user_id' => auth()->id()]);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder = BriefFolder::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'id' => $folder->id,
            'name' => $folder->name,
            'created_at' => $folder->created_at
        ], 201);
    }
}
