<?php

use App\Http\Controllers\Api\V1\Auth;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\ParkingController;
use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\Api\V1\ZoneController;
use App\Models\Parking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::get('profile', [Auth\ProfileController::class, 'show']);
    Route::put('profile', [Auth\ProfileController::class, 'update']);
    Route::put('password', Auth\PasswordUpdateController::class);
    Route::post('auth/logout', Auth\LogoutController::class);

    //Automatically, the Route::apiResource() will generate 5 API endpoints:
    //GET /api/v1/vehicles
    //POST /api/v1/vehicles
    //GET /api/v1/vehicles/{vehicles.id}
    //PUT /api/v1/vehicles/{vehicles.id}
    //DELETE /api/v1/vehicles/{vehicles.id}
    Route::apiResource('vehicles', VehicleController::class);

    Route::get('parkings', [ParkingController::class, 'index']);
    Route::get('parkings/history', [ParkingController::class, 'history']);
    Route::post('parkings/start', [ParkingController::class, 'start']);
    Route::get('parkings/{parking}', [ParkingController::class, 'show']);

    Route::bind('activeParking', function ($id) {
        return Parking::where('id', $id)->active()->firstOrFail();
    });
    Route::put('parkings/{activeParking}', [ParkingController::class, 'stop']);


});

//unprotected routes
Route::post('auth/register', Auth\RegisterController::class);
Route::post('auth/login', Auth\LoginController::class);
Route::get('zones', [ZoneController::class, 'index']);
