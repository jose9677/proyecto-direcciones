<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UbicGeograficController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

Route::middleware([JwtMiddleware::class])->group(function(){
    Route::prefix('auth')->controller(AuthController::class)->group(function (){
        Route::post('logout', 'logout');
        Route::put('active-user/{login}', 'activeUser');
        Route::put('desactive-user/{login}', 'desactiveUser');
    });      
}); 

Route::prefix('app')->controller(AuthController::class)->group(function (){
    Route::post('/login', 'login');
    Route::post('/','register');
    Route::post('register-employee','registerEmployee');
    Route::post('validate-user', 'validateUser');
    Route::post('validate-email', 'validateEmail');
    Route::put('change-password', 'changePassword');
})->withoutMiddleware([JwtMiddleware::class]);    


Route::prefix('ubic-geografic')->controller(UbicGeograficController::class)->group(function (){
    Route::get('get-edo/{id_edo}','getEdo');
    Route::get('get-mun/{id_edo}/{id_mun}','getMun');
    Route::get('get-prq/{id_mun}/{id_prq}','getPrq');
})->withoutMiddleware([JwtMiddleware::class]);