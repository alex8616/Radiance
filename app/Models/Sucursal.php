<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'latitud',
        'longitud'
    ];

    // Relaciones futuras
    public function doctores()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_sucursal');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function inventarios()
    {
        return $this->hasMany(ProductoSucursal::class);
    }

    public function sesiones()
    {
        return $this->hasMany(SesionTratamiento::class);
    }

    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'producto_sucursal',
            'sucursal_id',
            'producto_id'
        )->withPivot('precio', 'stock')->withTimestamps();
    }
}