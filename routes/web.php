<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusBrandController;
use App\Http\Controllers\BusinessCategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarBrandController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\EntrepreneurController;
use App\Http\Controllers\MobileMarketController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\TechnicalServiceController;
use App\Http\Controllers\TruckBrandController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserPhotoController;
use App\Http\Controllers\UserTagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleCategoryController;
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

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/photofeed', [HomeController::class, 'photoFeeds'])->name('home.photoFeeds');

Route::get('/mobilemarketer', [MobileMarketController::class, 'index']);

Route::get('/artisans', [ArtisanController::class, 'index']);

// Route::get('/contactus', [ContactUsController::class, 'index']);

Route::get('/getState', [SearchController::class, 'getState']);

// Route::get('/signup', [RegisterController::class, 'index']);

Route::resource('users', UserController::class);
// Authenticating some routes in the resource method
// Route::resource('users', UserController::class)->only(['store', 'show', 'edit', 'update', 'destroy'])->middleware('auth');
// Use the except here for the index and show route not to be disabled
// Route::resource('users', UserController::class)->except(['store', 'show', 'edit', 'update', 'destroy']);

// resource() method is for a resource controller
Route::resource('artisans', ArtisanController::class)->only('index', 'store', 'update');

// resource() method is for a resource controller
Route::resource('mobileMarketers', MobileMarketController::class)->only('index', 'store', 'update');

Route::resource('contactus', ContactUsController::class)->only('index', 'store', 'create');

Route::resource('technicalServices', TechnicalServiceController::class)->only('store', 'update');

Route::resource('entrepreneur', EntrepreneurController::class)->only('show');

Route::resource('businesscategory', BusinessCategoryController::class)->only('update');

Route::resource('address', AddressController::class)->only('update');

Route::resource('sparepart', SparePartController::class)->only('update');

Route::resource('carbrand', CarBrandController::class)->only('update');

Route::resource('busbrand', BusBrandController::class)->only('update');

Route::resource('truckbrand', TruckBrandController::class)->only('update');

Route::resource('vehiclecategory', VehicleCategoryController::class)->only('update');

Route::get('userlogin', [AuthController::class, 'create'])->name('login');

Route::post('userlogin', [AuthController::class, 'store'])->name('login.store');

Route::delete('userlogout', [AuthController::class, 'destroy'])->name('logout');



// Route::get('/not-found', function () {
//     return view('errors.404');
// })->name('notfound');








// Resource controller for business users
Route::get('/users/tag/{tag}', [UserTagController::class, 'index'])->name('users.tags.index');

Route::resource('users.comments', UserCommentController::class)->only(['store']);

Route::resource('users.photo', UserPhotoController::class)->only(['store', 'update']);

// create routes for and authentication
// Auth::routes();
