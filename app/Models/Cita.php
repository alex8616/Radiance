<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';

    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'sucursal_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'motivo',
        'observaciones'
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