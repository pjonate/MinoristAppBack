<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_venta'; // si el nombre no es plural, especifícalo

    protected $primaryKey = 'id_detalle'; // si no usas "id" como clave primaria

    protected $fillable = [
        'id_venta',
        'id_producto',
        'cantidad',
        'subtotal',
    ];

    // Relación con Product
    public function producto()
    {
        // 'id_producto' es la clave foránea en detalle_venta que apunta a products.id
        return $this->belongsTo(Product::class, 'id_producto', 'id');
    }

    // Relación con Sale
    public function venta()
    {
        // 'id_venta' es la clave foránea que apunta a sales.id_sale
        return $this->belongsTo(Sale::class, 'id_venta', 'id_sale');
    }
}

