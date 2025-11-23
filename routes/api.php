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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('contacts', \App\Http\Controllers\Api\ContactController::class);
Route::post('/contacts/{contact}/groups',[ContactController::class,'addGroups']);
Route::delete('/contacts/{contact}/groups/{group}',[ContactController::class,'removeGroup']);

Route::get('/groups',[GroupController::class,'index']);
Route::post('/groups',[GroupController::class,'store']);
Route::get('/groups/{group}',[GroupController::class,'show']);
Route::put('/groups/{group}',[GroupController::class,'update']);
Route::delete('/groups/{group}',[GroupController::class,'destroy']);
