<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodType;
use Illuminate\Http\Request;

class FoodTypeController extends Controller
{
    public function addFoodType(Request $request)
    {
        $data = $request ->validate([
            'food_type_name' => 'required|string',
        ]);

        $data = FoodType::create($data);

        return response()->json([
            'message' => 'Food type added successfully', 
            'data' => [$data]
        ], 201);
    }

    public function getFoodTypes()
    {
        $data = FoodType::select('food_type_id', 'food_type_name')->get();
        return response()->json([ 
            'data' => $data
        ], 200);
    }
}
