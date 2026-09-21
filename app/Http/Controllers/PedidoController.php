<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index()
    {
        return Pedido::with('producto')
            ->orderByDesc('fecha_pedido')
            ->orderByDesc('id_pedido')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_producto' => ['required', 'integer', 'exists:product,id'],
            'proveedor' => ['nullable', 'string', 'max:100'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'fecha_pedido' => ['required', 'date'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ]);

        $product = Product::findOrFail($validated['id_producto']);

        $pedido = Pedido::create([
            ...$validated,
            'proveedor' => $validated['proveedor'] ?? $product->proveedor,
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'message' => 'Pedido creado correctamente',
            'pedido' => $pedido->load('producto'),
        ], 201);
    }

    public function generateInventory(Pedido $pedido)
    {
        if ($pedido->estado !== 'pendiente') {
            return response()->json([
                'message' => 'Solo se puede generar inventario para pedidos pendientes',
            ], 422);
        }

        $pedido = DB::transaction(function () use ($pedido) {
            $lockedPedido = Pedido::whereKey($pedido->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedPedido->estado !== 'pendiente') {
                abort(422, 'El pedido ya fue procesado');
            }

            $product = Product::whereKey($lockedPedido->id_producto)
                ->lockForUpdate()
                ->firstOrFail();

            $product->increment('stock', $lockedPedido->cantidad);

            $lockedPedido->update([
                'estado' => 'recibido',
                'fecha_recepcion' => now()->toDateString(),
            ]);

            return $lockedPedido->load('producto');
        });

        return response()->json([
            'message' => 'Inventario generado correctamente',
            'pedido' => $pedido,
        ]);
    }
}