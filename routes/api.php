<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "api";
});

Route::prefix('auth')->group(function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
});


Route::prefix('catalogs')->middleware(JwtMiddleware::class)->group(function () {
    Route::resource('role', RoleController::class);
    Route::resource('user', UserController::class);
});
