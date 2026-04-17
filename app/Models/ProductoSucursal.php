<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoSucursal extends Model
{
    protected $table = 'producto_sucursal';

    protected $fillable = [
        'producto_id',
        'sucursal_id',
        'precio',
        'stock'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function sesiones()
    {
        return $this->belongsToMany(
            SesionTratamiento::class,
            'sesion_producto',
            'producto_sucursal_id',
            'sesion_id'
        )->withPivot([
            'detalle',
            'precio'
        ])->withTimestamps();
    }
}