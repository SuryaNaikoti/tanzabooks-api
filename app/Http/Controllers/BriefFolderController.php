<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        Log::info('BriefFolder creation attempt', ['data' => $request->all(), 'user_id' => Auth::id()]);

        // Simple validation as requested
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // For now, returning success message as requested
        return response()->json([
            'success' => true,
            'message' => 'Brief folder created successfully',
            'data' => [
                'name' => $request->name,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]
        ], 201);
    }
}
