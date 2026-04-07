<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntecedenteMedico extends Model
{
    protected $table = 'antecedentes_medicos';

    protected $fillable = [
        'paciente_id',
        'antecedentes_familiares',
        'antecedentes_personales',
        'anemia',
        'cardiopatias',
        'asma',
        'diabetes',
        'enfermedades_gastricas',
        'hepatitis',
        'tuberculosis',
        'epilepsia',
        'hipertension',
        'vih',
        'alergias',
        'en_tratamiento',
        'medicamentos',
        'hemorragia_extraccion'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}