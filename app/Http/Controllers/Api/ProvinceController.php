<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Province;

use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function getProvinces(){
        return response()->json(['data' => Province::all()], 200);
    }

    public function showPlaceInProvince(Request $request){
        // return response()->json(['data' => $request->input('province_code')], 200);
        $categoryNames = $request->input('categories');
        $data = $request->validate([
            'province_code' => 'required|exists:provinces,province_code'
        ]);

        $places = Place::with('village.province','placeType.categoryPlace','placeGallery')->whereHas('village.province', function ($query) use ($data) {
            $query->where('province_code', $data['province_code']);
        })
        ->where('status', '=', 1);
        if ($categoryNames) {
            $aCateName = json_decode($categoryNames);
            if (is_array($aCateName) && count($aCateName) > 0) {
                $places->whereHas('placeType.categoryPlace', function ($q) use ($aCateName) {
                    $q->whereIn('cat_place_name', $aCateName);
                });
            }
        }
        $places = $places->get();

        if ($places->isEmpty()) {
            return response()->json(['message' => 'No places found for the given province code'], 404);
        }
    
        $places->each(function ($place) {
            $averageRating = $place->placeRatings()->avg('rating');
            $place->average_rating = round($averageRating);
            $place->placeGallery->each(function ($gallery) {
                $gallery->place_image = basename($gallery->place_image); // Remove the path from the image
            });
        });
        return response()->json(['data' => $places], 200);
    }
}
