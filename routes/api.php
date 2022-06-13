<?php

use App\Http\Controllers\Api\BeauticianController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Beautician;
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
//test
Route::get('/beauticians', [BeauticianController::class, 'index']);


//book an appointment
Route::post('/book-appointment', [BeauticianController::class, 'bookAppointment']);


//get timeslots for a beautician
Route::get('/beauticians/{beautician_id}/timeslots', [BeauticianController::class, 'getTimeSlot']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
