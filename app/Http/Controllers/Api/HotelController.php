<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryHotel;
use App\Models\Hotel;
use App\Models\HotelGallery;
use App\Models\HotelType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function addHotel(Request $request)
    {
        // Validate incoming request data for hotels
        $data = $request->validate([
            'hotel_name' => 'required|string',
            'hotel_des' => 'required|string',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg',
            'village_code' => 'required|exists:villages,village_code',
            'cat_hotel_id' => 'required|exists:category_hotels,cat_hotel_id',
            'phone' => 'required|unique:hotels|numeric',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'price' => 'required|numeric',
            'status' => 'nullable|boolean',
        ]);

        // Create a new hotel instance
        $hotel = new Hotel($data);
        $hotel->status = $request->input('status', 1); // Default status to 1 if not provided
        $hotel->save();

        // Find or create the hotel category
        $categoryHotel = CategoryHotel::findOrFail($data['cat_hotel_id']);

        // Create a new hotel type instance
        $hotelType = new HotelType();
        $hotelType->hotel_type_name = $categoryHotel->cat_hotel_name; // Assign hotel_type_name from the request
        $hotelType->categoryHotel()->associate($categoryHotel);
        $hotelType->hotel()->associate($hotel);
        $hotelType->save();

        // Handle image upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $this->convertFilename($image->getClientOriginalName());
                $imagePath = '/images/hotel_gallery/';
                $image->move(public_path($imagePath), $imageName);

                // Create hotel gallery entry
                $gallery = new HotelGallery();
                $gallery->image = $imagePath . $imageName;
                $gallery->hotel()->associate($hotel);
                $gallery->save();
            }
        }

        return response()->json([
            'message' => 'Hotel and associated types created successfully',
            'data' => [$data],
        ], 201);
    }

    private function convertFilename($filename)
    {
        // Replace spaces with underscores
        return preg_replace('/\s+/', '_', $filename);
    }

    public function getAllHotel()
    {
        try {
            $hotels = Hotel::with(['village.province', 'hotelTypes', 'hotelGallery'])->get();
            $hotels->each(function ($hotel) {
                $averageRating = $hotel->hotelRatings()->avg('rating');
                $hotel->average_rating = round($averageRating);
                $hotel->hotelGallery->each(function ($gallery) {
                    $gallery->image = basename($gallery->image);
                });
            });
            return response()->json(['data' => $hotels], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving hotels'], 500);
        }
    }

    public function getHotel($hotel_id)
    {
        try {
            $hotel = Hotel::with('hotelTypes', 'hotelGallery')->findOrFail($hotel_id);
            $hotel->each(function ($hotel) {
                $hotel->hotelGallery->each(function ($gallery) {
                    $gallery->image = basename($gallery->image);
                });
            });

            return response()->json(['data' => $hotel], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Hotel not found'], 404);
        }
    }

    public function searchHotel(Request $request)
    {
        $searchQuery = $request->input('name');
        $query = Hotel::query();

        if ($searchQuery) {
            $query->where('hotel_name', 'like', '%' . $searchQuery . '%');
        }
        $hotels = $query->get();

        if ($hotels->isEmpty()) {
            return response()->json(['message' => 'No hotels found'], 200);
        }

        return response()->json(['data' => $hotels], 200);
    }

    public function getPopularHotels()
    {
        try {
            $hotels = Hotel::with(['village.province', 'hotelTypes', 'hotelGallery'])->get();
            
            $sortedHotel = $hotels->each(function ($hotel) {
                $averageRating = $hotel->hotelRatings()->avg('rating');
                $hotel->average_rating = round($averageRating);
                $hotel->hotelGallery->each(function ($gallery) {
                    $gallery->image = basename($gallery->image);
                });
                return $hotel;
            }) -> sortByDesc('average_rating');
            return response()->json(['data' => $sortedHotel->values()], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving hotels'], 500);
        }
    }
}
