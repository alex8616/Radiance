<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionProducto extends Model
{
    protected $table = 'sesion_producto';

    protected $fillable = [
        'sesion_id',
        'producto_id',
        'detalle',
        'precio'
    ];

    public function sesion()
    {
        return $this->belongsTo(SesionTratamiento::class, 'sesion_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}