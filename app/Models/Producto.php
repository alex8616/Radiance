<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio'
    ];

    public function sesiones()
    {
        return $this->belongsToMany(SesionTratamiento::class, 'sesion_producto')
            ->withPivot('cantidad', 'precio')
            ->withTimestamps();
    }
}