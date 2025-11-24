<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sale extends Model
{
    protected $table = 'sale'; // si el nombre no es plural, especifícalo

    protected $primaryKey = 'id_sale'; // si no usas "id" como clave primaria

    public $timestamps = false;

    protected $fillable = [
        'date',
        'total',
    ];
}