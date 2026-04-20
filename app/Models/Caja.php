<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $fillable = ['nombre',
                            'descripcion',
                            'sucursal_id',
                            'saldo_ingreso',
                            'saldo_egreso',
                            'saldo_total',
                            'saldo_ingresoQr',
                            'saldo_ingresoTarjeta'
                        ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class);
    }
}
