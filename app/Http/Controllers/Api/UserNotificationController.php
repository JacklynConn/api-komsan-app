<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Place;
use App\Models\Restaurant;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $notifications = UserNotification::with('notification')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $notifications->each(function ($notification) {
            $relatedData = null;
            switch ($notification->notification->type) {
                case 1:
                    $relatedData = Place::with(['village.province', 'placeGallery', 'placeType.categoryPlace', 'placeRatings'])->find($notification->notification->related_id);
                    if ($relatedData) {
                        $averageRating = $relatedData->placeRatings()->avg('rating');
                        $relatedData->average_rating = round($averageRating);
                        $relatedData->placeGallery->each(function ($gallery) {
                            $gallery->place_image = basename($gallery->place_image); // Remove the path from the image
                        });
                    }
                    break;
                case 2:
                    $relatedData = Hotel::with(['village.province', 'hotelGallery', 'hotelRatings'])->find($notification->notification->related_id);
                    if ($relatedData) {
                        $averageRating = $relatedData->hotelRatings()->avg('rating');
                        $relatedData->average_rating = round($averageRating);
                        $relatedData->hotelGallery->each(function ($gallery) {
                            $gallery->hotel_image = basename($gallery->hotel_image); // Remove the path from the image
                        });
                    }
                    break;
                case 3:
                    $relatedData = Restaurant::with(['village.province', 'restaurantGallery', 'restaurantType.categoryRestaurant', 'restaurantRatings'])->find($notification->notification->related_id);
                    if ($relatedData) {
                        $averageRating = $relatedData->restaurantRatings()->avg('rating');
                        $relatedData->average_rating = round($averageRating);
                        $relatedData->restaurantGallery->each(function ($gallery) {
                            $gallery->restaurant_image = basename($gallery->restaurant_image); // Remove the path from the image
                        });
                    }
                    break;
                default:
                    $relatedData = $notification->slider;
            }
            $notification->related_data = $relatedData;
        });

        return response()->json(['notifications' => $notifications], 200);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = UserNotification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($notification) {
            $notification->status = false; // false indicates the notification has been read
            $notification->save();
            return response()->json(['message' => 'Notification marked as read'], 200);
        }

        return response()->json(['message' => 'Notification not found or unauthorized'], 404);
    }

    public function countUnreadNotifications(Request $request)
    {
        $count = UserNotification::where('user_id', $request->user()->id)
            ->where('status', true)
            ->count();
        return response()->json(['count' => $count], 200);
    }

    public function deleteNotification(Request $request, $id)
    {
        $notification = UserNotification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Notification deleted'], 200);
        }

        return response()->json(['message' => 'Notification not found or unauthorized'], 404);
    }

    //delete all notifications
    public function deleteAllNotifications(Request $request)
    {
        $notifications = UserNotification::where('user_id', $request->user()->id)->get();
        foreach ($notifications as $notification) {
            $notification->delete();
        }
        return response()->json(['message' => 'All notifications deleted'], 200);
    }
}
