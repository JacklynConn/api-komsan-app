<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryHotel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryHotelController extends Controller
{
    public function addCatHotel(Request $request)
    {
        $data = $request->validate([
            'cat_hotel_name' => 'required|string',
        ]);

        $data = CategoryHotel::create($data);

        return response()->json([
            'message' => 'Hotel Category added successfully', 
            'data' => [$data]
        ], 201);
    }

    public function getCatHotel() {
        $data = CategoryHotel::select('cat_hotel_id','cat_hotel_name')->get();
        return response()->json([
            'data' => $data
        ], 200);
    }
}
