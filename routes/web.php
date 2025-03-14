<?php

use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MechanicsController;
use App\Http\Controllers\BusinessUserController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\EntrepreneurController;
use App\Http\Controllers\MobileMarketController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TechnicalServiceController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserPhotoController;
use App\Http\Controllers\UserTagController;
use App\Http\Controllers\UserController;
use App\Models\BlogPost;
use App\Models\TechnicalService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/whosabiwork', function () {
//     return inertia('Index/Index');
// });

Route::get('/whosabiwork', [HomeController::class, 'index'])->name('home.index');

Route::get('/photofeed', [HomeController::class, 'photoFeeds'])->name('home.photoFeeds');

Route::get('/mobilemarketer', [MobileMarketController::class, 'index']);

Route::get('/artisans', [ArtisanController::class, 'index']);

Route::get('/contactus', [ContactUsController::class, 'index']);

Route::get('/getState', [SearchController::class, 'getState']);

// Route::post('/getStateData', [SearchController::class, 'getStateData']);

// Route::get('/signup', [RegisterController::class, 'index']);

Route::resource('users', UserController::class);

// resource() method is for a resource controller
Route::resource('artisans', ArtisanController::class);

// resource() method is for a resource controller
Route::resource('mobileMarketers', MobileMarketController::class);

Route::resource('contactUs', ContactUsController::class);

Route::resource('technicalServices', TechnicalServiceController::class)->only('store');
// Route::post('/technicalServices', [TechnicalService::class, 'store']);







// Using authorization to protect a URL
// We would use a 'can' middleware to do the protection.
Route::get('/secret', [HomeController::class, 'secret'])
    ->name('home.secret')  
    ->middleware('can:home.secret');

// Resource controller for business users
Route::resource('businessUser', BusinessUserController::class);

Route::resource('mechanics', MechanicsController::class);

Route::resource('entrepreneur', EntrepreneurController::class);

Route::get('/businessPage', function () {
    return "This is a business public page";
})->name('businessPage');

Route::get('/businessProfile', [BusinessUserController::class, 'index'])->name('businessProfile');

Route::get('/userProfile', function () {
    return view('regularUser.profile');
})->name('userProfile');

Route::get('/users/tag/{tag}', [UserTagController::class, 'index'])->name('users.tags.index');

Route::resource('users.comments', UserCommentController::class)->only(['store']);

Route::resource('users.photo', UserPhotoController::class)->only(['store', 'update']);

// create routes for and authentication
Auth::routes();
