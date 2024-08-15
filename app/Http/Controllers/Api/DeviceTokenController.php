<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $deviceToken = $request->device_token;

        $device = DeviceToken::where('device_token', $deviceToken)->first();
        if ($device) {
            $device->device_token = $deviceToken;
            $device->save();
        } else {
            $device = new DeviceToken();
            $device->user_id = $user->id;
            $device->device_token = $deviceToken;
            $device->save();
        }

        return response()->json(['message' => 'Device token saved successfully'], 200);
    }
}
