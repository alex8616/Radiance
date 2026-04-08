<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TratamientoPaciente extends Model
{
    protected $table = 'tratamientos_paciente';

    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'sucursal_id',
        'nombre_tratamiento',
        'descripcion',
        'fecha_inicio',
        'fecha_fin_estimada',
        'costo_total',
        'estado'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}