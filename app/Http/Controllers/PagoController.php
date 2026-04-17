<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\TratamientoPaciente;
use Illuminate\Support\Str;

class PagoController extends Controller
{
    public function guardarAdelanto(Request $request){
        //return response()->json($request);

        $request->validate([
            'tratamiento_id' => 'required|exists:tratamientos_paciente,id',
            'pagos' => 'required|array|min:1'
        ]);

        $grupo = \Illuminate\Support\Str::uuid();

        $tratamiento = TratamientoPaciente::find($request->tratamiento_id);
        
        foreach ($request->pagos as $pago) {
            Pago::create([
                'tratamiento_id' => $request->tratamiento_id,
                'sesion_id' => null,
                'paciente_id' => $tratamiento->paciente_id,
                'monto' => $pago['monto'],
                'metodo_pago' => $pago['metodo'],
                'fecha' => now(),
                'grupo_pago' => $grupo,
            ]);
        }

        $pacienteId = $tratamiento->paciente_id;

        return response()->json($pacienteId);
    }
}
