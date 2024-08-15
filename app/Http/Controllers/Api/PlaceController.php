<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryPlace;
use App\Models\Place;
use App\Models\PlaceGallery;
use App\Models\PlaceType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


class PlaceController extends Controller
{
    public function addplace(Request $request){
        $data = $request->validate([
            // Validate incoming request data for places
            'place_name' => 'required|string',
            'place_des' => 'required|string',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg',
            'village_code' => 'required|exists:villages,village_code',
            'cat_place_id' => 'required|exists:category_places,cat_place_id',
            'phone' => 'nullable|unique:places|numeric',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);
         // Create a new place instance
        $place = new Place($data);
        $place->status = $request->input('status', 1); // Default status to 1 if not provided
        $place->save();

        // Find or create the place category
        $categoryPlace = CategoryPlace::findOrFail($data['cat_place_id']);


        $placeType = new PlaceType();
        $placeType->cat_place_id = $categoryPlace->cat_place_id; // Assign place_type_name from the request
        //$place->categoryPlace()->associate($categoryPlace);
        $placeType->place()->associate($place);
        $placeType->save();

            // Handle image upload
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $place_image) {
                    $imageName = time() . '_' . $place_image->getClientOriginalName();
                    $imagePath = '/images/place_gallery/';
                    $place_image->move(public_path($imagePath), $imageName);
        
                    // Create hotel gallery entry
                    $gallery = new PlaceGallery();
                    $gallery->place_image = $imagePath . $imageName; // Store only the relative path
                    $gallery->place()->associate($place);
                    $gallery->save();
                    $data['images'][] = $gallery->place_image;
                }
            }
                   
        return response() -> json([
            'message' => 'Place and associated types created successfully',
            'data' => [$data],
        ],201);
    }
    public function getPlace($place_id)
    {
        try {
            $place = Place::with('placeType','placeGallery')->findOrFail($place_id);
            return response()->json(['data' => $place], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Place not found'], 404);
        }
    }


    public function searchPlace(Request $request)
    {
        try {
            $searchQuery = $request->input('name');
            $categoryNames = $request->input('categories');
            $query = Place::query();
            if ($categoryNames) {
                $aCateName = json_decode($categoryNames);
                if (is_array($aCateName) && count($aCateName) > 0) {
                    $query->whereHas('placeType.categoryPlace', function ($q) use ($aCateName) {
                        $q->whereIn('cat_place_name', $aCateName);
                    });
                }
            }
            if ($searchQuery) {
                $query->where('place_name', 'like', '%' . $searchQuery . '%');
                $query->where('status', '=', 1);
            }
            $places = $query->with([
                'village.province',
                'placeType.categoryPlace',
                'placeGallery',
            ])->get();
            $places->each(function ($place) {
                $averageRating = $place->placeRatings()->avg('rating');
                $place->average_rating = round($averageRating, 1);
                $place->placeGallery->each(function ($gallery) {
                    $gallery->place_image = basename($gallery->place_image);
                });
            });
            return response()->json(['data' => $places], 200);
    
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'No places found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
    
    
    
    

    
    
    
    

    
    
    
    
    
    

    

    public function getPopularPlace()
    {
        try {
            $places = Place::with(['village.province', 'placeType.categoryPlace', 'placeGallery'])->get();

            $sortedPlace = $places->each(function ($place) {
                $averageRating = $place->placeRatings()->avg('rating');
                $place->average_rating =round($averageRating);
                $place->placeGallery->each(function ($gallery) {
                    $gallery->place_image = basename($gallery->place_image);
                });
                return $place;
            }) -> sortByDesc('average_rating');

            return response()->json(['data' => $sortedPlace->values()], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving restaurants'], 500);
        }

    }
    public function getAllTypes()
    {
        $types = CategoryPlace::all();
        return response()->json(['data' => $types], 200);
    }
}
