<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono'
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
}