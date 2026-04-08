<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionTratamiento extends Model
{
    protected $table = 'sesiones_tratamiento';

    protected $fillable = [
        'tratamiento_id',
        'cita_id',
        'fecha',
        'descripcion',
        'observaciones'
    ];

    public function tratamiento()
    {
        return $this->belongsTo(TratamientoPaciente::class, 'tratamiento_id');
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}