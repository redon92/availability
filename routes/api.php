<?php

use App\Http\Controllers\FlightSearchController;
use App\Http\Controllers\UserAvailabilityController;
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

//Route::middleware('auth:api')->group(function () {
    Route::post('availability/store', [UserAvailabilityController::class, 'store'])->name('availability.store');
    Route::post('availability/update-all', [UserAvailabilityController::class, 'updateAll'])->name('availability.updateall');
    Route::get('availability/get', [UserAvailabilityController::class, 'getAvailability'])->name('availability.get');
//});
Route::get('/search-flights', [FlightSearchController::class, 'searchFlights']);

