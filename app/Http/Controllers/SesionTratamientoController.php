<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesionTratamiento;
use App\Models\TratamientoPaciente;
use Illuminate\Support\Str;
use App\Models\Pago;

class SesionTratamientoController extends Controller
{
    
   public function CrearSesion(Request $request, $tratamientoId){
        $tratamiento = TratamientoPaciente::find($tratamientoId);

        $validated = $request->validate([
            'fechaAtencion' => 'required|date',
            'analisis' => 'required|string|max:255',
            'planAccion' => 'required|string',
            'firma' => 'nullable|string|max:255',
            'pagos' => 'nullable|string'
        ]);

        $sesion = SesionTratamiento::create([
            'sucursal_id' => $tratamiento->sucursal_id,
            'tratamiento_id' => $tratamientoId,
            'fecha_atencion' => $validated['fechaAtencion'],
            'analisis' => $validated['analisis'],
            'plan_accion' => $validated['planAccion'],
            'costo' => 0,
            'saldo' => 0,
            'firma' => $validated['firma'] ?? null,
        ]);

        $pagos = json_decode($request->pagos, true);

        if (!empty($pagos)) {

            $grupo = Str::uuid();

            foreach ($pagos as $pago) {
                Pago::create([
                    'tratamiento_id' => $tratamientoId,
                    'paciente_id' => $tratamiento->paciente_id,
                    'sesion_id' => $sesion->id,
                    'monto' => $pago['monto'],
                    'metodo_pago' => $pago['metodo'],
                    'fecha' => now(),
                    'grupo_pago' => $grupo,
                ]);
            }
        }

        $pacienteId = $tratamiento->paciente_id;

        return response()->json($pacienteId);
    }
}
