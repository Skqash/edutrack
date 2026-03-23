<?php

use App\Http\Controllers\Api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Search API routes for dynamic dropdowns
Route::middleware('auth')->group(function () {
    Route::get('/courses/search', [SearchController::class, 'courses']);
    Route::get('/subjects/search', [SearchController::class, 'subjects']);
    Route::get('/teachers/search', [SearchController::class, 'teachers']);
    Route::get('/departments/search', [SearchController::class, 'departments']);
});
