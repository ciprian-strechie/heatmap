<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkJourneyController;
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

Route::get('link_journeys/counter_page/{page}/{start_time}/{end_time}', [LinkJourneyController::class, 'counterPageByTimeInterval']);
Route::get('link_journeys/counter_url/{start_time}/{end_time}/{search_url}', [LinkJourneyController::class, 'counterUrlByTimeInterval'])->where('search_url', '.*');

Route::get('link_journeys/customer/{customer_id}', [LinkJourneyController::class, 'customerJourney']);
Route::get('link_journeys/customers_same_journey/{customer_id}', [LinkJourneyController::class, 'customersSameJourney']);

Route::post('link_journeys', [LinkJourneyController::class, 'store']);
