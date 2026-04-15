<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'tratamiento_id',
        'paciente_id',
        'sesion_id',
        'monto',
        'fecha',
        'metodo_pago',
        'referencia'
    ];

    public function tratamiento()
    {
        return $this->belongsTo(TratamientoPaciente::class, 'tratamiento_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function sesion()
    {
        return $this->belongsTo(SesionTratamiento::class, 'sesion_id');
    }
}