<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\AddressController;

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


Route::prefix('people')->group(function () {
    Route::get('/', [PersonController::class, 'index']);
    Route::post('/', [PersonController::class, 'store']);
    Route::get('/{person}', [PersonController::class, 'show']);
    Route::put('/{person}', [PersonController::class, 'update']);
    Route::delete('/{person}', [PersonController::class, 'destroy']);


    Route::post('/{person}/addresses', [AddressController::class, 'store']);
    Route::get('/{person}/address-history', [AddressController::class, 'history']);
    Route::get('/{person}/active-address', [AddressController::class, 'activate']);
});

