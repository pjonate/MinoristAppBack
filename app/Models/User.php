<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;//se utiliza la calse Model de Eloquent
use Illuminate\Foundation\Auth\User as Authenticatable; //se importa la clase Authenticable

class user extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $fillable = ['name', 'password'];
}
