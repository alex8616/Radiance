<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionTratamiento extends Model
{
    protected $table = 'sesiones_tratamiento';

    protected $fillable = [
        'tratamiento_id',
        'sucursal_id', // 🔥 NUEVO
        'fecha',
        'fecha_atencion',
        'observaciones',
        'analisis',
        'plan_accion',
        'costo',
        'saldo',
        'firma'
    ];

    public function tratamiento()
    {
        return $this->belongsTo(TratamientoPaciente::class, 'tratamiento_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'sesion_id');
    }

    // 🔥 CAMBIO CLAVE
    public function productos()
    {
        return $this->belongsToMany(
            ProductoSucursal::class,
            'sesion_producto',
            'sesion_id',
            'producto_sucursal_id'
        )->withPivot([
            'detalle',
            'precio'
        ])->withTimestamps();
    }
}