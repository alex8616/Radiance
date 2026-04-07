<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'ci',
        'fecha_nacimiento',
        'lugar_nacimiento',
        'telefono',
        'direccion',
        'ocupacion',
        'estado_civil',
        'sexo'
    ];
}