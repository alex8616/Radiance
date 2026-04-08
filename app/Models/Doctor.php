<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctores';

    protected $fillable = [
        'nombre',
        'especialidad',
        'telefono'
    ];

    public function sucursales()
    {
        return $this->belongsToMany(Sucursal::class, 'doctor_sucursal')
            ->withPivot('dia_semana', 'hora_inicio', 'hora_fin');
    }
}