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
        'categoria_id',
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

    // 🔥 RELACIÓN CLAVE
    public function categoria()
    {
        return $this->belongsTo(CategoriaTratamiento::class, 'categoria_id');
    }

    public function sesiones()
    {
        return $this->hasMany(SesionTratamiento::class, 'tratamiento_id')
                    ->orderBy('fecha', 'asc');
    }
}