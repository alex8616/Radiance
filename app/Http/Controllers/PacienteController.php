<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function buscar(Request $request){
        $search = $request->search;

        $pacientes = Paciente::where('nombre', 'LIKE', "%$search%")
            ->orWhere('ci', 'LIKE', "%$search%")
            ->orWhere('apellido_paterno', 'LIKE', "%$search%")
            ->orWhere('apellido_materno', 'LIKE', "%$search%")
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $pacientes
        ]);
    }

    public function show($id)
    {
        $paciente = Paciente::find($id);

        if (!$paciente) {
            return response()->json([
                'success' => false,
                'message' => 'Paciente no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $paciente
        ]);
    }
}
