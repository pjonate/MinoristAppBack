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
    return Response::make(File::get(public_path('app/index.html')), 200, [
        'Content-Type' => 'text/html; charset=UTF-8',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ]);
})->where('any', '.*');

//Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user']);