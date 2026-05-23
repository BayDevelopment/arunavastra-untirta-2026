<?php

use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

// Mengarahkan domain utama ke /monitoring
Route::redirect('/', '/monitoring');

// Controller route
Route::get('/monitoring', [MonitoringController::class, 'index']);
