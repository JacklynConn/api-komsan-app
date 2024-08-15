<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodType;
use App\Models\Restaurant;
use App\Models\RestaurantGallery;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function addRestaurant(Request $request)
    {
        // Validate the request
        $data = $request->validate([
            'res_name' => 'required|string',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg',
            'res_des' => 'required|string',
            'village_code' => 'required|exists:villages,village_code',
            'food_type_id' => 'required|array',
            'food_type_id.*' => 'required|exists:food_types,food_type_id',
            'food_price' => 'required|array',
            'food_price.*' => 'required|numeric',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'res_email' => 'nullable|email',
            'res_phone' => 'required|unique:restaurants|numeric',
            'res_web' => 'nullable|string',
            'open_time' => 'required|string',
            'close_time' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        // Create and save the restaurant
        $restaurant = new Restaurant($data);
        $restaurant->status = $request->input('status', 1); // Default status to 1 if not provided
        $restaurant->save();

        // Create and save the food
        foreach ($data['food_type_id'] as $index => $food_type_id) {
            $foodtype = FoodType::findOrFail($food_type_id);
            $food = new Food();
            $food->food_name = $foodtype->food_type_name; // Set the food name to the food type name
            $food->food_price = $data['food_price'][$index]; // Ensure each price corresponds to the correct food type
            $food->foodType()->associate($foodtype);
            $food->restaurant()->associate($restaurant);
            $food->save();
        }

        // Handle image upload and create the gallery entry
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) 
            {
                $imageName = time() .'_'.$this->convertFilename($image->getClientOriginalName());
                $imagePath = '/images/restaurants/';
                $image->move(public_path($imagePath), $imageName);

                $gallery = new RestaurantGallery();
                $gallery-> image = $imagePath . $imageName;
                $gallery->restaurant()->associate($restaurant);
                $gallery->save();
            }     
        }

        return response()->json([
            'message' => 'Restaurant and associated foods added successfully',
            'data' => $restaurant->load('foods', 'restaurantGallery'), // Include the newly created foods and gallery in the response
        ], 201);
    }

    private function convertFilename($filename) {
        // Replace spaces with underscores
        return preg_replace('/\s+/', '_', $filename);
    }

    public function getAllRestaurant()
    {
        try {
            $restaurants = Restaurant::with(['village.province', 'foods', 'restaurantGallery'])->get();
        
            $restaurants->each(function ($restaurant) {
                $averageRating = $restaurant->resRating->avg('rating');
                $restaurant->average_rating = round($averageRating);
                if ($restaurant->restaurantGallery) {
                    $restaurant->restaurantGallery->each(function ($gallery) {
                        $gallery->image = basename($gallery->image);
                    });
                }
            });
        
            return response()->json(['data' => $restaurants], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving restaurants'], 500);
        }
        
    }


    public function searchRestaurant(Request $request)
    {
        $search = $request->input('name');
        $restaurant = Restaurant::where('res_name', 'like', '%' . $search . '%')->get();

        if ($restaurant->isEmpty()) {
            return response()->json([
                'message' => 'No restaurants found',
            ], 200);
        }
        return response()->json(['restaurants' => $restaurant], 200); // return response
    }

    public function getPopularRes()
    {
        try {
            $restaurants = Restaurant::with(['village.province', 'foods', 'restaurantGallery'])->get();

            $sortedRes = $restaurants->each(function ($restaurant) {
                $averageRating = $restaurant->resRating->avg('rating');
                $restaurant->average_rating =round($averageRating);
                $restaurant->restaurantGallery->each(function ($gallery) {
                    $gallery->image = basename($gallery->image);
                });
                return $restaurant;
            }) -> sortByDesc('average_rating');

            return response()->json(['data' => $sortedRes->values()], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving restaurants'], 500);
        }

    }
    
}
