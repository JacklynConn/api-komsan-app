<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'version' => 'required|string'
        ]);

        $version = new AppVersion();
        $version->version = $request->version;
        $version->save();

        return response()->json([
            'message' => 'App version created successfully!',
            'version' => $version
        ], 201);
    }

    public function getLatestVersion()
    {
        $latestVersion = AppVersion::orderBy('id', 'desc')->first();

        if($latestVersion){
            return response()->json([
                'version' => $latestVersion->version
            ], 200);
        } else {
            return response()->json([
                'message' => 'No version found'
            ], 404);
        }
    }
}
