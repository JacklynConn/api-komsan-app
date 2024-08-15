<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PlaceRating;

class PlaceRatingController
{
    //
    public function ratingPlace(Request $request)
    {
        $place_id = $request->input ('place_id');
        $user_id = Auth::user()->id;

        $place_rating = DB::table('place_ratings')
            ->where('place_id', $place_id)
            ->where('user_id', $user_id)
            ->get()
            ->Count();

        if ($place_rating > 0) {
            $place = PlaceRating::where("user_id",$user_id )->where("place_id", $place_id)->first();
            $place->rating = $request->rating;
            $place->comment= $request->comment;
            $place->save();
        } else {
            $newrating = new PlaceRating;
            $newrating->place_id = $place_id;
            $newrating->user_id = $user_id;
            $newrating->rating = $request->rating;
            $newrating->comment= $request->comment;
            $newrating->save();
        }
        return response(
            [
                'message' => 'Rating added successfully',
                'data' => $place_rating,
            ],
            200
        );
    }

    public function getUserRating($place_id){
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user_id = Auth::id();
        $userRating = PlaceRating :: where ('place_id', $place_id)
                    -> where ('user_id', $user_id)
                    -> first();
        if (!$userRating) {
            return response()->json(['message' => 'No rating found for this hotel by the user'], 404);
        }
        return response()->json(['userRating' => $userRating], 200);
        }
}
