<?php

use App\Http\Controllers\VendorApiController;
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

Route::controller(VendorApiController::class)
     ->prefix('vendor')
     ->group(function(){
        Route::get('/single-mac-lookup','singleMacLookUp');
        Route::post('/multiple-mac-lookup','multipleMacLookUp');
    });