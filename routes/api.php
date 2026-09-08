<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;

//RUTAS PÚBLICAS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//RUTAS PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {

    // Productos
    Route::post('/product', [ProductController::class, 'create']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/all', [ProductController::class, 'all']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::get('/products/buscar', [ProductController::class, 'buscarProducto']);
    Route::get('/producto/{codigo}', [ProductController::class, 'findByCode']);
    Route::get('/products/search', [ProductController::class, 'searchProducts']);


    // Ventas
    Route::post('/detail', [SaleController::class, 'create']);
    Route::post('/venta', [SaleController::class, 'venta']);
    Route::post('/sales', [SaleController::class, 'store']);

    // Reportes
    Route::get('/report/productos-mas-rentables', [ReportController::class, 'productosMasRentables']);
    Route::get('/report/ventas-diarias', [ReportController::class, 'ventasDiarias']);
    Route::get('/report/productos-stock-bajo', [ReportController::class, 'productosStockBajo']);
    Route::get('/report/productos-sin-ventas', [ReportController::class, 'productosSinVentasAntiguos']);

});

