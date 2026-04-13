<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntecedenteMedico extends Model
{
    use HasFactory;

    protected $table = 'antecedentes_medicos';

    protected $fillable = [
        'paciente_id',
        'antecedentes_familiares',
        'otros',
        'alergias',
        'anemia',
        'asma',
        'cardiopatias',
        'diabetes',
        'epilepsia',
        'hipertension',
        'tuberculosis',
        'vih',
        'embarazo',
        'en_tratamiento',
        'recibe_tratamiento',
        'hemorragia_extraccion',
        'ultima_visita',
        'fuma',
        'bebe',
        'protesis',
        'cepillo',
        'hilo',
        'enjuague',
        'frecuencia',
        'sangrado',
    ];

    // Relación con paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
