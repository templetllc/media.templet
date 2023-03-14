<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});

//Routes Request External
//Route::get('getImages/{category}/{preset}/{date}/{tags}', [App\Http\Controllers\Pages\ViewImageController::class, 'getImages']);
Route::get('getImages/{category}/{preset}/{date}/{tags}/{width?}/{height?}/{page?}', [App\Http\Controllers\MediaController::class, 'getImages']);
Route::get('getCategories', [App\Http\Controllers\MediaController::class, 'getCategories']);
Route::get('getPreset/{category?}', [App\Http\Controllers\MediaController::class, 'getPreset']);
Route::get('getTags/{category?}/{view?}', [App\Http\Controllers\MediaController::class, 'getTags']);
Route::get('thumbExists/{image}', [App\Http\Controllers\MediaController::class, 'thumbExists']);
//Route::get('getImages/{category}/{preset}/{date}/{tags}', 'MediaController@getImages');


Route::get('getImagesAddin/{category}/{preset}/{date}/{tags}/{view}/{page?}', [App\Http\Controllers\MediaController::class, 'getImagesAddin']);

Route::get('getImagesTest/{category}/{preset}/{date}/{tags}/{width?}/{height?}/{page?}', [App\Http\Controllers\MediaController::class, 'getImagesTest']);