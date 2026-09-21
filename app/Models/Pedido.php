<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';

    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'id_producto',
        'proveedor',
        'cantidad',
        'estado',
        'fecha_pedido',
        'fecha_recepcion',
        'observaciones',
    ];

    protected $casts = [
        'fecha_pedido' => 'date:Y-m-d',
        'fecha_recepcion' => 'date:Y-m-d',
    ];

    public function producto()
    {
        return $this->belongsTo(Product::class, 'id_producto', 'id');
    }
}