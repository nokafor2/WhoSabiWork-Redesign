<?php

use App\Http\Controllers\Api\ArtisanController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SparePartController;
use App\Http\Controllers\Api\StateController;
use App\Http\Controllers\Api\TechnicalServiceController;
use App\Http\Controllers\Api\TownController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VehicleBrandController;
use App\Http\Controllers\Api\VehicleCategoryController;
use Doctrine\DBAL\Schema\Index;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('artisan', ArtisanController::class);
Route::apiResource('user', UserController::class);
Route::apiResource('states', StateController::class)->only('store');
Route::apiResource('towns', TownController::class)->only('store');
Route::apiResource('technicalServices', TechnicalServiceController::class)->only('store');
Route::apiResource('spareParts', SparePartController::class)->only('store');
Route::apiResource('vehicleCategories', VehicleCategoryController::class)->only('store');
Route::apiResource('vehicleBrands', VehicleBrandController::class)->only('store');
Route::apiResource('search', SearchController::class)->only('store');
// Route::get('/artisans', [ArtisanController::class, index()]);