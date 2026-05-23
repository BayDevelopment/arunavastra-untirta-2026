<?php

use App\Http\Controllers\Api\BoatLocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/boat/location', [BoatLocationController::class, 'store']);
Route::get('/boat/location/latest', [BoatLocationController::class, 'latest']);
Route::get('/boat/location/history', [BoatLocationController::class, 'history']);
