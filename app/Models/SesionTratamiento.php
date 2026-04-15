<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionTratamiento extends Model
{
    protected $table = 'sesiones_tratamiento';

    protected $fillable = [
        'tratamiento_id',
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

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'sesion_id');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'sesion_producto')
            ->withPivot('cantidad', 'precio')
            ->withTimestamps();
    }

}