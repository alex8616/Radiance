<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctores';

    protected $fillable = [
        'user_id',
        'nombre',
        'especialidad',
        'telefono'
    ];

    // 🔗 relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 relación con sucursales
    public function sucursales()
    {
        return $this->belongsToMany(Sucursal::class, 'doctor_sucursal')
            ->withTimestamps();
    }
}