<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    protected $table = 'movimientos_caja';

    protected $fillable = [
        'caja_id',
        'tipo',
        'categoria',
        'monto',
        'fecha',
        'descripcion',
        'tratamiento_id',
        'paciente_id',
        'metodo_pago'
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function tratamiento()
    {
        return $this->belongsTo(TratamientoPaciente::class, 'tratamiento_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
