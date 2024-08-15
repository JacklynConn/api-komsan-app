<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite_res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestaurantFavController extends Controller
{
    public function resFavorite(Request $request)
    {
        $user_id = Auth::user()->id;
        $res_id = $request ['res_id'];
        
        $fav_res = DB::table('favorite_res')
            ->where('res_id', $res_id)
            ->where('user_id', $user_id)
            ->count();

        if ($fav_res > 0) {
            DB::table('favorite_res')
                ->where('res_id', $res_id)
                ->where('user_id', $user_id)
                ->delete();
            return response([
                        "message" => "Removed from favorites successfully",
                        "data" => ["is_favorite" => false]
                    ], 200);
        } else {
           $new_fav = new Favorite_res();
           $new_fav->res_id = $res_id;
           $new_fav->user_id = $user_id;
           $new_fav->save();
           return response([
                        "message" => "Added to favorites successfully",
                        "data" => ["is_favorite" => true]
                    ], 200);
        }
    }

    public function getResFavStatus(Request $request)
    {
        $user = $request->user();
        $resFavorites = Favorite_res::where('user_id', $user->id)->get();

        foreach ($resFavorites as $resFavorite){
            if($resFavorite->status = 1){
                $resFavorite->status = true;
            }
        }
        return response([
            'data' => $resFavorites
        ], 200);
    }

    public function getResFavs(Request $request)
    {
        $user = $request->user();
        $resFavorites = Favorite_res::where('user_id', $user->id)
            ->with('restaurant', 'restaurant.village.province', 'restaurant.foods', 
                'restaurant.restaurantGallery')
            ->get();

            $resFavorites->each(function ($resFavorite) {
                // Calculate the average rating for the restaurant
                $averageRating = $resFavorite->restaurant->resRating->avg('rating');
                $averageRating = round($averageRating);
                $resFavorite->restaurant->average_rating = $averageRating;
        
                // Clean up image paths
                $resFavorite->restaurant->restaurantGallery->each(function ($gallery) {
                    $gallery->image = basename($gallery->image);
                });
        
                // Set status correctly
                $resFavorite->status = $resFavorite->status == 1;
            });

        return response([
            'data' => $resFavorites
        ], 200);
    }


}
