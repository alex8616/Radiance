<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesionTratamiento;
use App\Models\TratamientoPaciente;

class SesionTratamientoController extends Controller
{
    
    public function CrearSesion(Request $request, $tratamientoId){
        $validated = $request->validate([
            'fechaAtencion' => 'required|date',
            'analisis'      => 'required|string|max:255',
            'planAccion'    => 'required|string',
            'costo'         => 'required|numeric|min:0',
            'saldo'         => 'required|numeric|min:0',
            'firma'         => 'nullable|string|max:255',
        ]);

        // Crear sesión asociada al tratamiento
        $sesion = new SesionTratamiento();
        $sesion->tratamiento_id = $tratamientoId;
        $sesion->fecha = $validated['fechaAtencion'];
        $sesion->fecha_atencion = $validated['fechaAtencion'];
        $sesion->analisis = $validated['analisis'];
        $sesion->plan_accion = $validated['planAccion'];
        $sesion->costo = $validated['costo'];
        $sesion->saldo = $validated['saldo'];
        $sesion->firma = $validated['firma'] ?? null;
        $sesion->save();

        return response()->json([
            'message' => 'Sesión registrada correctamente',
            'sesion'  => $sesion
        ], 201);
    }
}
