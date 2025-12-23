<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoProducto extends Model
{
    use HasFactory;

    protected $table = 'catalogo_productos';

    protected $fillable = [
        'codigo','nombre_producto','descripcion','imagen_path','precio','precio_prov','proveedor','observaciones','activo'
    ];
}
