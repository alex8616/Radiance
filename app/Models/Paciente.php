<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Paciente extends Model
{
    protected $table = 'pacientes';

    protected $fillable = [
        'created_by',
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
        'sexo',
        'imagen'
    ];

    // 🔗 Usuario que registró el paciente
    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // App\Models\Paciente.php
    public function antecedenteMedico()
    {
        return $this->hasOne(AntecedenteMedico::class, 'paciente_id');
    }

}