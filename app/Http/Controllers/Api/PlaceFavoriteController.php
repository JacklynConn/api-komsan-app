<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoritePlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlaceFavoriteController extends Controller
{
    public function placeFavorite (Request $request){

        $user_id = Auth::user()->id;
        $place_id = $request->place_id;

        $fav_palace = DB :: table('favorite_places')
            ->where('place_id', $place_id)
            ->where('user_id', $user_id)
            ->count();

            if($fav_palace > 0){
                DB::table('favorite_places')
                    ->where('place_id', $place_id)
                    ->where('user_id', $user_id)
                    ->delete();
                    return response(
                        [
                            "message" => "Removed from favorites successfully",
                            "data" => ["is_favorite" => false] // Indicating it has been removed
                        ], 200);
            }else{
                $new_fav = new FavoritePlace();
                $new_fav->place_id = $place_id;
                $new_fav->user_id = $user_id;
                $new_fav->save();
                return response(
                    [
                        "message" => "Added to favorites successfully",
                        "data" => ["is_favorite" => true] // Indicating it has been added
                    ], 200);
            }
    }

    public function getFavPlaceStatus(Request $request)
    {
        $user = $request->user();
        $placeFavorites = FavoritePlace::where('user_id', $user->id)->get();

        foreach ($placeFavorites as $placeFavorite) {
            if ($placeFavorite->status == 1) {
                $placeFavorite->status = true;
            }
        }
        return response([
            'data' => $placeFavorites
        ], 200);
    }

    public function getFavPlaces(Request $request)
    {
        $user = $request->user();
        $favoritePlaces = FavoritePlace::where('user_id', $user->id)
            ->with('place', 'place.village.province', 'place.placeType.categoryPlace', 'place.placeGallery')->get();
        $favoritePlaces->each(function ($favorite) {

            $averageRating = $favorite->place->placeRatings->avg('rating');
            $favorite->place->average_rating = round($averageRating);
            $favorite->place->placeGallery->each(function ($gallery) {
                $gallery->place_image = basename($gallery->place_image);
            });
            $favorite->status = $favorite-> status=1;   
        });

        return response()->json(['data' => $favoritePlaces]);
    }
}
