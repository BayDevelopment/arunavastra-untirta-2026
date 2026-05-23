<?php

use App\Http\Controllers\Api\BoatLocationController;
use App\Http\Controllers\HardwareController;
use App\Http\Controllers\MonitoringController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
