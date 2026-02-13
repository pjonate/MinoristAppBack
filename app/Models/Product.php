<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $table = 'product'; // si el nombre no es plural, especifícalo

    protected $primaryKey = 'id'; // si no usas "id" como clave primaria

    protected $fillable = [
        'codigo',
        'descripcion',
        'categoria',
        'proveedor',
        'precio',
        'stock',
        "unidad_venta"
    ];
}