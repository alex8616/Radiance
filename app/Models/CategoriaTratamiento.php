<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaTratamiento extends Model
{
    protected $table = 'categorias_tratamientos';

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function tratamientos()
    {
        return $this->hasMany(TratamientoPaciente::class, 'categoria_id');
    }
}
