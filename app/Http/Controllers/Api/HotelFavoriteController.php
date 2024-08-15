<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HotelFavoriteController extends Controller
{
    public function hotelFavorite (Request $request)
    {
        $user_id = Auth::user()->id;
        $hotel_id = $request ['hotel_id'];
        
        // Check if the hotel is already a favorite
        $fav_hotel= DB::table('hotel_favorites')
        ->where('hotel_id', $hotel_id)
        ->where('user_id', $user_id)
        ->count();

        if ($fav_hotel > 0) { // Hotel is already a favorite, so remove it
            DB :: table('hotel_favorites')
                ->where('hotel_id', $hotel_id)
                ->where('user_id', $user_id)
                ->delete();
                return response(
                    [
                        "message" => "Removed from favorites successfully",
                        "data" => ["is_favorite" => false] // Indicating it has been removed
                    ], 200);
        } else { // Hotel is not a favorite, so add it
            $new_fav = new HotelFavorite(); 
            $new_fav->hotel_id = $hotel_id;
            $new_fav->user_id = $user_id;
            $new_fav->save();   
            return response(
                [
                    "message" => "Added to favorites successfully",
                    "data" => ["is_favorite" => true] // Indicating it has been added
                ], 200);
        }
    }

    public function getFavHotelStatus(Request $request)
    {
        $user = $request->user();
        $hotelFavorites = HotelFavorite::where('user_id', $user->id)->get();

        foreach ($hotelFavorites as $hotelFavorite) {
            if ($hotelFavorite->status == 1) {
                $hotelFavorite->status = true;
            }
        }
        return response([
            'data' => $hotelFavorites
        ], 200);
    }

    public function getFavHotels(Request $request)
    {
        $user = $request->user();
        $favoriteHotels = HotelFavorite::where('user_id', $user->id)
            ->with('hotel', 'hotel.village.province', 'hotel.hotelTypes', 
            'hotel.hotelGallery', 'hotel.hotelRatings')->get();
        $favoriteHotels->each(function ($favorite) {

            $averageRating = $favorite->hotel->hotelRatings->avg('rating');
            $favorite->hotel->average_rating = round($averageRating);

            $favorite->hotel->hotelGallery->each(function ($gallery) {
                $gallery->image = basename($gallery->image);
            });
        });

        return response()->json(['data' => $favoriteHotels]);
    }
}
