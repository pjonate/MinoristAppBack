<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\AuthController;

/*Route::get('/', function () {
    return view('welcome');
});*/

/*
Route::get('/products', [ProductController::class, 'index']);
//Route::post('/products', [ProductController::class, 'store']);
*/

Route::get('/{any}', function () {
    return File::get(public_path('react/index.html'));
})->where('any', '.*');

//Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user']);