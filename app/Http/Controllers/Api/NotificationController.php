<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // public function getNotifications(Request $request)
    // {
    //     $notifications = Notification::orderBy('created_at', 'desc')->get();
    //     return response()->json(['notifications' => $notifications], 200);
    //     // $notifications = Notification::whereHas('users', function ($query) use ($request) {
    //     //     $query->where('user_id', $request->user()->id);
    //     // })->orderBy('created_at', 'desc')->get();
    //     // return response()->json(['notifications' => $notifications], 200);
    // }
}
