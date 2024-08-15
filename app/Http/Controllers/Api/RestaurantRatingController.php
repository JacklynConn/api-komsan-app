<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\RestaurantRating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestaurantRatingController
{
    public function ratingRestaurant(Request $request)
    {
        $validated = $request->validate([
            'res_id' => 'required|exists:restaurants,res_id',
            'rating' => 'required|numeric|min:0.5|max:5',
            'comment' => 'nullable|string',
        ]);

        RestaurantRating::updateOrCreate(
            ['res_id' => $validated['res_id'], 'user_id' => auth()->id()],
            ['rating' => $validated['rating'], 'comment' => $request->comment]
        );

        return response()->json(['message' => 'Rating added successfully.']);
    }

    public function getUserRating($res_id)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $user_id = Auth::id();

    $userRating = RestaurantRating::where('res_id', $res_id)
        ->where('user_id', $user_id)
        ->first();

    if (!$userRating) {
        return response()->json(['message' => 'No rating found for this restaurant by the user'], 404);
    }
    return response()->json(['userRating' => $userRating], 200);
}


    public function getAverageRating($res_id)
    {
        $averageRating = RestaurantRating::where('res_id', $res_id)->average('rating');
        return response()->json(['averageRating' => $averageRating]);
    }



    // public function ratingRestaurant(Request $request){
    //     $res_id = $request->input('res_id');
    //     $user_id = Auth::user()->id;
    //     $res_rating = DB::table('restaurant_ratings')
    //         ->where('res_id', $res_id)
    //         ->where('user_id', $user_id)
    //         ->get()
    //         ->Count();
    // if($res_rating >0){
    //     $restaurant = RestaurantRating::where("user_id",$user_id)->where("res_id",$res_id)->first();
    //     $restaurant->rating=$request->rating;
    //     $restaurant->comment=$request->comment;
    //     $restaurant->save();
    // }else{
    //     $newrating = new RestaurantRating;
    //     $newrating->user_id = $user_id;
    //     $newrating->res_id = $res_id;
    //     $newrating->rating = $request->rating;
    //     $newrating->comment = $request->comment;
    //     $newrating->save();
    // }
    // return response(
    //     [
    //         'message' => 'Rating added successfully',
    //         'data' => $res_rating,
    //     ],
    //     200 );
    // }

}
