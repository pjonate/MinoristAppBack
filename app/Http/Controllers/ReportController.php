<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\DetalleVenta;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function productosMasRentables(Request $request)
    {
        $hoy = Carbon::now();
        // Puedes recibir un filtro de fecha opcional
        $periodo = $request->query('periodo', 'dia');

        switch($periodo) {
            case 'dia':
                $inicio = $hoy->copy()->startOfDay();
                $fin = $hoy->copy()->endOfDay();
                break;

            case 'semana':
                $inicio = $hoy->copy()->startOfWeek();
                $fin = $hoy->copy()->endOfWeek();
                break;

            case 'mes':
                $inicio = $hoy->copy()->startOfMonth();
                $fin = $hoy->copy()->endOfMonth();
                break;

            case 'todos':
            default:
                $inicio = null;
                $fin = null;
                break;
        }

        /*dd([
            'periodo' => $periodo,
            'inicio' => $inicio,
            'fin' => $fin
        ]);*/

        $query = DetalleVenta::select('id_producto', \DB::raw('SUM(subtotal) as total_ventas'))
                ->groupBy('id_producto');

        if ($inicio && $fin) {
            $query->whereHas('venta', function($q) use ($inicio, $fin) {
                $q->whereBetween('date', [$inicio, $fin]);
            });
        }

        $topProductos = $query
            ->orderBy('total_ventas', 'desc')
            ->take(10)
            ->get();

        // Para devolver también el nombre del producto
        $topProductos = $topProductos->map(function ($item) {
            return [
                'id_producto' => $item->id_producto,
                'descripcion' => $item->producto->descripcion, // relación producto
                'total_ventas' => $item->total_ventas,
            ];
        });

        return response()->json($topProductos);

    }

    public function ventasDiarias(Request $request)
    {
        $hoy = Carbon::now();
        // Puedes recibir un filtro de fecha opcional
        $periodo = $request->query('periodo', 'semana');
        
        switch($periodo){
            case 'semana':
                // Últimos 7 días (incluye hoy)
                $inicio = $hoy->copy()->subDays(6)->startOfDay();
                $fin    = $hoy->copy()->endOfDay();

                // Agrupar ventas por día
                $ventas = Sale::select(
                        \DB::raw('DATE(date) as fecha'),
                        \DB::raw('SUM(total) as total_ventas')
                    )
                    ->whereBetween('date', [$inicio, $fin])
                    ->groupBy('fecha')
                    ->orderBy('fecha')
                    ->get()
                    ->keyBy('fecha');

                // Garantizar los 7 días aunque tengan 0 ventas
                $resultado = collect();
                for ($i = 0; $i < 7; $i++) {
                    $dia = $hoy->copy()->subDays(6 - $i)->format('Y-m-d');
                    $resultado->push([
                        'fecha' => $dia,
                        'total_ventas' => $ventas[$dia]->total_ventas ?? 0,
                    ]);
                }

                return response()->json($resultado);

            case 'mes':
                $inicio = $hoy->copy()->subMonths(11)->startOfMonth();
                $fin    = $hoy->copy()->endOfMonth();

                // Agrupar ventas por mes
                $ventas = Sale::select(
                        \DB::raw('DATE_FORMAT(date, "%Y-%m") as mes'),
                        \DB::raw('SUM(total) as total_ventas')
                    )
                    ->whereBetween('date', [$inicio, $fin])
                    ->groupBy('mes')
                    ->orderBy('mes')
                    ->get()
                    ->keyBy('mes');

                // Garantizar los 12 meses aunque tengan 0 ventas
                $resultado = collect();
                for ($i = 11; $i >= 0; $i--) {
                    $mes = $hoy->copy()->subMonths($i)->format('Y-m');
                    $resultado->push([
                        'mes' => $mes,
                        'total_ventas' => $ventas[$mes]->total_ventas ?? 0,
                    ]);
                }

                return response()->json($resultado);
            
        }
        return response()->json(['error' => 'Periodo inválido'], 400);
    }

    public function productosStockBajo()
    {
        $productos = Product::select('id', 'descripcion', 'stock')
            ->orderBy('stock', 'asc')   // menor stock primero
            ->take(5)                   // solo 5
            ->get();

        return response()->json($productos);
    }

    public function productosSinVentasAntiguos()
    {
        // Obtener fecha de última venta por producto
        $productos = \DB::table('product as p')
            ->leftJoin('detalle_venta as dv', 'p.id', '=', 'dv.id_producto')
            ->leftJoin('sale as v', 'dv.id_venta', '=', 'v.id_sale')
            ->select(
                'p.id',
                'p.descripcion',
                'p.stock',
                \DB::raw('MAX(v.date) as ultima_venta')
            )
            ->groupBy('p.id', 'p.descripcion', 'p.stock')
            ->orderBy('ultima_venta', 'asc')   // primero los más antiguos
            ->limit(5)
            ->get();

        // Formatear salida
        return response()->json($productos);
    }

}
