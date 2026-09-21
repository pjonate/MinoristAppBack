<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PedidoController;

//RUTAS PÚBLICAS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//RUTAS PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {

    // Productos
    Route::post('/product', [ProductController::class, 'create']);
    Route::post('/product/import', [ProductController::class, 'import']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/all', [ProductController::class, 'all']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::get('/products/buscar', [ProductController::class, 'buscarProducto']);
    Route::get('/producto/{codigo}', [ProductController::class, 'findByCode']);
    Route::get('/products/search', [ProductController::class, 'searchProducts']);

    // Pedidos
    Route::get('/pedidos', [PedidoController::class, 'index']);
    Route::post('/pedidos', [PedidoController::class, 'store']);
    Route::post('/pedidos/{pedido}/generar-inventario', [PedidoController::class, 'generateInventory']);


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

