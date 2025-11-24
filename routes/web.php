<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/products', [ProductController::class, 'index']);
//Route::post('/products', [ProductController::class, 'store']);

Route::get('/{any}', function () {
    return File::get(public_path('react/index.html'));
})->where('any', '.*');
