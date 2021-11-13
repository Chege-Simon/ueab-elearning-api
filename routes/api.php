<?php

use App\Http\Controllers\PhotoController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

// Route::resource('photos', PhotoController::class);
// Route::resource('videos', VideoController::class);
// Route::get('/photos',[PhotoController::class, 'index']});

// protected routes
Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::post('/photos',[PhotoController::class, 'store']);
    Route::delete('/photos/{id}',[PhotoController::class, 'destroy']);
    Route::put('/photos/{id}',[PhotoController::class, 'update']);
    Route::post('/videos',[VideoController::class, 'store']);
    Route::delete('/videos/{id}',[VideoController::class, 'destroy']);
    Route::put('/videos/{id}',[VideoController::class, 'update']);
    Route::post('/videos/{id}',[VideoController::class, 'download']);
    Route::get('/logout',[AuthController::class, 'logout']);
    Route::resource('/users', UserController::class);
});

//public routes
Route::get('/photos',[PhotoController::class, 'index']);
Route::get('/photos/{id}',[PhotoController::class, 'show']);
Route::get('/videos',[VideoController::class, 'index']);
Route::get('/videos/{id}',[VideoController::class, 'show']);

//login and registration
Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
