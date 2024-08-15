<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HotelRating;
use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HotelRatingController
{
    //
    public function ratingHotel(Request $request)
    {
        $hotel_id = $request->input('hotel_id');
        $user_id = Auth::user()->id;

        $hotel_rating = DB::table('hotel_ratings')
            ->where('hotel_id', $hotel_id)
            ->where('user_id', $user_id)
            ->get()
            ->Count();
        //return $user_id;

        if ($hotel_rating > 0) {
            $hotel = HotelRating::where("user_id",$user_id )->where("hotel_id", $hotel_id)->first();
            $hotel->rating = $request->rating;
            $hotel->comment= $request->comment;
            $hotel->save();

        }else{
            $newrating = new HotelRating;
            $newrating->hotel_id = $hotel_id;
            $newrating->user_id = $user_id;
            $newrating->rating = $request->rating;
            $newrating->comment = $request->comment;
            $newrating->save();
        }
        return response(
            [
                'data' => $hotel_rating,
                'message' => 'Hotel Rating Added Successfully',

            ],
            200
        );
    }

    public function getUserRating($hotel_id) {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user_id = Auth::id();

        $userRating = HotelRating::where('hotel_id', $hotel_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$userRating) {
            return response()->json(['message' => 'No rating found for this hotel by the user'], 404);
        }
        return response()->json(['userRating' => $userRating], 200);
        }

        public function getAverageRating($hotel_id)
        {
            $averageRating = HotelRating::where('hotel_id', $hotel_id)->average('rating');
            return response()->json(['averageRating' => $averageRating]);
        }


}
