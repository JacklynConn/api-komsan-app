<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\Slider;
use App\Models\Place;
use App\Models\Hotel;
use App\Models\Notification;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SliderController extends Controller
{
    protected FirebaseService $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|numeric|in:0,1,2,3',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date'
        ]);

        $relatedId = null;
        if ($request->type != 0) {
            if (!isset($request->relatedId)) {
                return response()->json(['message' => 'Please provide related id'], 400);
            }
            switch ($request->type) {
                case 1:
                    $relatedId = Place::find($request->relatedId);
                    break;
                case 2:
                    $relatedId = Hotel::find($request->relatedId);
                    break;
                case 3:
                    $relatedId = Restaurant::find($request->relatedId);
                    break;
                default:
                    return response()->json(['message' => 'Invalid type'], 400);
            }
            if (!$relatedId) {
                return response()->json(['message' => 'Related entity not found'], 404);
            } else {
                $relatedId = $request->relatedId;
            }
        }

        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addYear();
        if (isset($request->start_date) && isset($request->end_date)) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            if ($startDate->startOfDay() < Carbon::now()->startOfDay()) {
                return response()->json([
                    'message' => 'Start date cannot be less than current date',
                    'current_date' => Carbon::now()->toDateString()
                ], 400);
            }

            if ($startDate > $endDate) {
                return response()->json(['message' => 'Start date cannot be greater than end date'], 400);
            }
        }

        // Create the slider
        $slider = Slider::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'relatedId' => $relatedId,
            'start_date' => $startDate->format('Y-m-d H:i:s'),
            'end_date' => $endDate->format('Y-m-d H:i:s'),
            'active_status' => 1,
            'image' => $request->image
        ]);

        // Handle the image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $sliderImageName = time() . '.' . $image->getClientOriginalExtension();
            $sliderImagePath = '/images/slider_gallery/';
            $image->move(public_path($sliderImagePath), $sliderImageName);
            $slider->image = $sliderImagePath . $sliderImageName;
            $slider->save();
        }

        // Store Notification in the database
        $notification = Notification::create([
            'title' => 'New Notification',
            'body' => $slider->title . '. Check it out now!',
            'image' => $slider->image,
            'type' => $slider->type,
            'related_id' => $slider->relatedId,
            'type' => $slider->type,
            'status' => 1
        ]);

        // Send push notification
        $deviceTokens = DeviceToken::pluck('device_token')->toArray();
        $users = User::all();

        foreach ($deviceTokens as $deviceToken) {
            try {
                $this->firebaseService->sendNotification(
                    $deviceToken,
                    'New Notification',
                    $slider->title . '. Check it out now!',
                    [
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'title' => 'New Notification',
                        'body' => $slider->title . '. Check it out now!',
                        'image' => $slider->image,
                        'type' => $slider->type,
                        'relatedId' => $slider->relatedId,
                    ],
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }


        # Store notification for each user in the device_notifications with idslider and status
        foreach ($users as $user) {
            UserNotification::create([
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'status' => true
            ]);
        }

        return response()->json(['message' => 'Slider created successfully', 'slider' => $slider], 201);
    }

    // protected function schedule(Schedule $schedule)
    // {
    //     Log::info('Updating slider status...');
    //     try {
    //         $schedule->call(function () {
    //             $sliders = \App\Models\slider::where('end_date', '<', \Carbon\Carbon::now())->update(['active_status' => 0]);
    //         })->everyTwoMinutes();
    //         Log::info('Slider status updated successfully');

    //     }
    //     catch (\Exception $e) {
    //         Log::error('Error updating slider status: ' . $e->getMessage());
    //     }
    // }

    public function index(Request $request)
    {
        $type = $request->query('type');
        $relatedId = $request->query('relatedId');
        $query = Slider::where('active_status', 1);
        if (!is_null($type)) {
            $query->where('type', $type);
        }

        if (!is_null($relatedId)) {
            $query->where('relatedId', $relatedId);
        }
        $sliders = $query->get();
        $sliders->each(function ($slider) {
            if ($slider->type == 1) {
                $place =  Place::with(['village.province', 'placeGallery', 'placeType.categoryPlace', 'placeRatings'])->find($slider->relatedId);
                $averageRating = $place->placeRatings()->avg('rating');
                $place->average_rating = round($averageRating);
                $place->placeGallery->each(function ($gallery) {
                    $gallery->place_image = basename($gallery->place_image); // Remove the path from the image
                });
                $slider->related_data = $place;
            } elseif ($slider->type == 2) {
                $hotel = Hotel::with(['village.province', 'hotelGallery', 'hotelTypes.categoryHotel', 'hotelRatings'])->find($slider->relatedId);
                $averageRating = $hotel->hotelRatings()->avg('rating');
                $hotel->average_rating = round($averageRating);
                $hotel->hotelGallery->each(function ($gallery) {
                    $gallery->image = basename($gallery->image);
                });
                $slider->related_data = $hotel;
            } elseif ($slider->type == 3) {
                $restaurant = Restaurant::with(['village.province', 'restaurantGallery', 'foods', 'resRating'])->find($slider->relatedId);
                $averageRating = $restaurant->resRating->avg('rating');
                $restaurant->average_rating = round($averageRating);
                if ($restaurant->restaurantGallery) {
                    $restaurant->restaurantGallery->each(function ($gallery) {
                        $gallery->image = basename($gallery->image);
                    });
                }
                $slider->related_data = $restaurant;
            } else {
                $slider->related_data = null;
            }
        });
        return response()->json(['data' => $sliders], 200);
    }
}
