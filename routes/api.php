<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;

Route::post('/product', [ProductController::class, 'create']);
Route::get('/products', [ProductController::class, 'index']);
Route::delete('/product/{id}', [ProductController::class, 'destroy']);
Route::put('/product/{id}', [ProductController::class, 'update']);
Route::get('/products/buscar', [ProductController::class, 'buscarProducto']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/detail', [SaleController::class, 'create']);
Route::post('/venta', [SaleController::class, 'venta']);

Route::get('/report/productos-mas-rentables', [ReportController::class, 'productosMasRentables']);
Route::get('/report/ventas-diarias', [ReportController::class, 'ventasDiarias']);
Route::get('/report/productos-stock-bajo', [ReportController::class, 'productosStockBajo']);
Route::get('/report/productos-sin-ventas', [ReportController::class, 'productosSinVentasAntiguos']);

