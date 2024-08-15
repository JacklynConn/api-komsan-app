<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Place;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getAllLoactions()
    {
        $places = Place::all();
        $hotels = Hotel::all();
        $restaurants = Restaurant::all();

        $locations = [];

        foreach ($places as $place) {
            $locations[] = [
                'id' => $place->place_id,
                'name' => $place->place_name,
                'type' => 'place',
                'latitude' => $place->latitude,
                'longitude' => $place->longitude,
            ];
        }

        foreach ($hotels as $hotel) {
            $locations[] = [
                'id' => $hotel->hotel_id,
                'name' => $hotel->hotel_name,
                'type' => 'hotel',
                'latitude' => $hotel->latitude,
                'longitude' => $hotel->longitude,
            ];
        }

        foreach ($restaurants as $restaurant) {
            $locations[] = [
                'id' => $restaurant->res_id,
                'name' => $restaurant->res_name,
                'type' => 'restaurant',
                'latitude' => $restaurant->latitude,
                'longitude' => $restaurant->longitude,
            ];
        }

        return response()->json($locations);
    }
}
