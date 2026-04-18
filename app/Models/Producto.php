<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sucursal;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio'
    ];

    public function sesiones()
    {
        return $this->belongsToMany(SesionTratamiento::class, 'sesion_producto')
            ->withPivot('detalle', 'precio')
            ->withTimestamps();
    }

    // 🔥 relación con inventario
    public function inventarios()
    {
        return $this->hasMany(ProductoSucursal::class);
    }

    public function sucursales()
    {
        return $this->belongsToMany(
            Sucursal::class,
            'producto_sucursal',
            'producto_id',
            'sucursal_id'
        )->withPivot('precio', 'stock')->withTimestamps();
    }
}