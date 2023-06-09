<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => 'auth:sanctum'], function(){
    
    Route::prefix('group')->group(function(){

        Route::post('create', [GroupController::class, 'createGroup']);
        Route::post('join', [GroupController::class, 'joinUser']);
        Route::get('show/{public_id}', [GroupController::class, 'show']);
        Route::get('index', [GroupController::class, 'index']);
    });
});
