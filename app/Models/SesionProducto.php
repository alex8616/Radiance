<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionProducto extends Model
{
    protected $table = 'sesion_producto';

    protected $fillable = [
        'sesion_id',
        'producto_sucursal_id',
        'detalle',
        'precio'
    ];

    public function sesion()
    {
        return $this->belongsTo(SesionTratamiento::class, 'sesion_id');
    }

    public function productoSucursal()
    {
        return $this->belongsTo(ProductoSucursal::class);
    }
}