<?php

use App\Http\Controllers\Api\AppVersionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryHotelController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\HotelRatingController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\RestaurantRatingController;
use App\Http\Controllers\Api\FoodTypeController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\PlaceRatingController;
use App\Http\Controllers\Api\CategoryPlaceController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\HotelFavoriteController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\PlaceFavoriteController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\RestaurantFavController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\UserNotificationController;
use Illuminate\Support\Facades\Route;

//AUTH
Route::post("register",     [AuthController::class, "register"]);
Route::post("login",        [AuthController::class, "login"]);
Route::post("logout",       [AuthController::class, "logout"]);
Route::post("check-phone",  [AuthController::class, "checkPhone"]);
Route::post("verify",       [AuthController::class, "verify"]);
Route::post("forgot-password", [AuthController::class, "forgetPassword"]);
Route::post("verify-reset-password", [AuthController::class, "verifyResetCode"]);
Route::post("reset-password", [AuthController::class, "resetPassword"]);

//HOTEL
Route::post("add-hotel", [HotelController::class, "addHotel"]);
Route::post("search-hotel", [HotelController::class, "searchHotel"]);
Route::get("get-all-hotel", [HotelController::class, "getAllHotel"]);
Route::get("get-hotel/{hotel_id}", [HotelController::class, "getHotel"]);
Route::post("add-cat-hotel", [CategoryHotelController::class, "addCatHotel"]);
Route::get("hotel/category", [CategoryHotelController::class, "getCatHotel"]);
Route::get("hotel/get-average-rating/{hotel_id}", [HotelRatingController::class, "getAverageRating"]);
Route::get("hotel/popular", [HotelController::class, "getPopularHotels"]);


//Restaurant
Route::post("add-restaurant", [RestaurantController::class, "addRestaurant"]);
Route::get("get-all-res", [RestaurantController::class, "getAllRestaurant"]);
Route::post("add-foodtype", [FoodTypeController::class, "addFoodType"]);
Route::get("get-foodtype", [FoodTypeController::class, "getFoodTypes"]);
Route::post("search-restaurant", [RestaurantController::class, "searchRestaurant"]);
Route::get("restaurant/get-average-rating/{res_id}", [RestaurantRatingController::class, "getAverageRating"]);
Route::get("restaurant/popular", [RestaurantController::class, "getPopularRes"]);

//Place

Route::post("add-place", [PlaceController::class, "addPlace"]);
Route::post("add-cat-place", [CategoryPlaceController::class, "addCatPlace"]);
Route::get('get-place/{place_id}', [PlaceController::class, 'getPlace']);
Route::get("get-cat-place/{cat_place_id}", [CategoryPlaceController::class, "getCatPlace"]);
Route::post('places/search', [PlaceController::class, 'searchPlace']);
Route::get('place/popular', [PlaceController::class, 'getPopularPlace']);
Route::get('alltypes', [PlaceController::class, 'getAllTypes']);

//random image
Route::post('sliders', [SliderController::class, 'store']);
Route::get('slidersdata', [SliderController::class, 'index']);


//Province
Route::get('provinces', [ProvinceController::class, 'getProvinces']);
Route::post("showPlaceInProvince", [ProvinceController::class, 'showPlaceInProvince']);
Route::get('locations', [LocationController::class, 'getAllLoactions']);

Route::get("popular-hotel", [HotelController::class, "getPopularHotels"]);

// App version
Route::post('add-app-version', [AppVersionController::class, 'store']);
Route::get('get-app-version', [AppVersionController::class, 'getLatestVersion']);

Route::middleware('auth:api')->group(function () {
    Route::post('hotels/add-favorite', [HotelFavoriteController::class, 'hotelFavorite']);
    Route::get('hotels/get-favorite-status', [HotelFavoriteController::class, 'getFavHotelStatus']);
    Route::get('hotels/get-favorite', [HotelFavoriteController::class, 'getFavHotels']);
    Route::get("hotel/get-user-rating/{hotel_id}", [HotelRatingController::class, "getUserRating"]);
    Route::post("hotel/rating", [HotelRatingController::class, "ratingHotel"]);

    Route::post('restaurants/add-favorite', [RestaurantFavController::class, 'resFavorite']);
    Route::get('restaurants/get-favorite-status', [RestaurantFavController::class, 'getResFavStatus']);
    Route::get('restaurants/get-favorite', [RestaurantFavController::class, 'getResFavs']);
    Route::post("restaurant/rating", [RestaurantRatingController::class, "ratingRestaurant"]);
    Route::get("restaurant/get-user-rating/{res_id}", [RestaurantRatingController::class, "getUserRating"]);

    Route::post('places/add-favorite', [PlaceFavoriteController::class, 'placeFavorite']);
    Route::get('places/get-favorite-status', [PlaceFavoriteController::class, 'getFavPlaceStatus']);
    Route::get('places/get-favorite', [PlaceFavoriteController::class, 'getFavPlaces']);
    Route::post("place/rating", [PlaceRatingController::class, "ratingPlace"]);
    Route::get("place/get-user-rating/{place_id}", [PlaceRatingController::class, "getUserRating"]);

    Route::get('me', [AuthController::class, 'me']);
    Route::post("device-tokens", [DeviceTokenController::class, "store"]);
    Route::get("get-notification", [UserNotificationController::class, "getNotifications"]);
    Route::post("mark-read/{id}", [UserNotificationController::class, "markAsRead"]);
    Route::get("count-unread-notification", [UserNotificationController::class, "countUnreadNotifications"]);
    Route::post("delete-notification/{id}", [UserNotificationController::class, "deleteNotification"]);
    Route::post("delete-all-notification", [UserNotificationController::class, "deleteAllNotifications"]);
});
