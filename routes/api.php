<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

Route::middleware([JwtMiddleware::class])->group(function(){
    Route::prefix('auth')->controller(AuthController::class)->group(function (){
        Route::post('logout', 'logout');
    });      
}); 

Route::prefix('app')->controller(AuthController::class)->group(function (){
    Route::post('/login', 'login');
    Route::post('/','register');
    Route::post('register-employee','registerEmployee');
    /* Route::put('validate-email', 'validateEmail');
    Route::put('change-password/{identity}', 'changePassword'); */
})->withoutMiddleware([JwtMiddleware::class]);    
