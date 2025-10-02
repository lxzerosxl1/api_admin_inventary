<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $fillable = [
        'name',
        'descripcion',
        'pais',
        'departamento',
        'provincia',
        'distrito',
        'direccion',
        'referencia',
        'nota',
        'activo'
    ];
}
