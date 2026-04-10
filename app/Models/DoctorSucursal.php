<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSucursal extends Model
{
    protected $table = 'doctor_sucursal';

    protected $fillable = [
        'doctor_id',
        'sucursal_id',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}