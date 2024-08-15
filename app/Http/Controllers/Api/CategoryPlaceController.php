<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryPlace;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryPlaceController extends Controller
{
    public function addCatPlace(Request $request)
    {
        $data = $request->validate([
            'cat_place_name' => 'required|string',
        ]);

        $data = CategoryPlace::create($data);

        return response()->json([
            'message' => 'Place Category added successfully', 
            'data' => [$data]
        ], 201);
    }
    public function getCatPlace($cat_hotel_id)
    {
        try {
            // Retrieve the category place
            $categoryPlace = CategoryPlace::findOrFail($cat_hotel_id);
            
            // Retrieve all places associated with the category
            $places = $categoryPlace->placeTypes()->with('place')->get()->pluck('place');
    
            return response()->json(['data' => $places], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Category place not found'], 404);
        }
    }
}
