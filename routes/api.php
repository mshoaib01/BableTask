<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiTaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [ApiAuthController::class, 'login']);

// Public routes of Task
Route::controller(ApiTaskController::class)->group(function() {
    Route::get('/tasks', 'index');
    Route::get('/tasks/show/{id}', 'show');
});


// Protected routes of Task and logout
Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(ApiTaskController::class)->group(function() {
        Route::post('/tasks/store', 'store');
        Route::post('/tasks/update/{id}', 'update');
        Route::post('/tasks/destroy/{id}', 'destroy');
    });
});

Route::any('{any}', function(){
    return response()->json([
        'status'    => false,
        'message'   => 'Page Not Found.',
    ], 404);
})->where('any', '.*'); 