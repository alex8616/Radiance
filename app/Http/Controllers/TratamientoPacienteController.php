<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaTratamiento;
use App\Models\TratamientoPaciente;
use App\Models\Doctor;
use App\Models\SesionTratamiento;
use Illuminate\Support\Facades\Auth;


class TratamientoPacienteController extends Controller
{
    public function GetCategorias()
    {
        $categorias = CategoriaTratamiento::all();
        return response()->json($categorias);
    }

    public function CrearTratamiento(Request $request, $pacienteId){
        $doctorId = Doctor::where('user_id', auth()->id())->value('id');
        $sucursalId = session('sucursal_id');

        if (!$doctorId) {
            return response()->json(['error' => 'Doctor no encontrado'], 400);
        }

        if (!$sucursalId) {
            return response()->json(['error' => 'Sucursal no seleccionada'], 400);
        }

        $validated = $request->validate([
            'categoria_id'      => 'required|exists:categorias_tratamientos,id',
            'descripcion'       => 'nullable|string',
            'fecha_inicio'      => 'required|date',
            'fecha_fin_estimada'=> 'nullable|date',
            'costo_total'       => 'required|numeric|min:0',
        ]);

        $tratamiento = TratamientoPaciente::create([
            'paciente_id'       => $pacienteId,
            'doctor_id'         => $doctorId,
            'sucursal_id'       => $sucursalId, // 🔥 AQUÍ ESTÁ LA MAGIA
            'categoria_id'      => $validated['categoria_id'],
            'descripcion'       => $validated['descripcion'],
            'fecha_inicio'      => $validated['fecha_inicio'],
            'fecha_fin_estimada'=> $validated['fecha_fin_estimada'],
            'costo_total'       => $validated['costo_total'],
            'estado'            => 'activo', // 🔥 minúscula
        ]);

        return response()->json([
            'success' => true,
            'tratamiento' => $tratamiento
        ]);
    }

    public function GetTratamientoSesion($tratamientoId){
        $tratamiento = TratamientoPaciente::with([
            'sesiones',
            'pagos',
            'sesiones.productos.producto' // 🔥 CLAVE
        ])->find($tratamientoId);

        if (!$tratamiento) {
            return response()->json(['error' => 'Tratamiento no encontrado'], 404);
        }

        return response()->json($tratamiento);
    }

    public function TratamientosGet(){
        $user = auth()->user();
        $query = TratamientoPaciente::query();
        if ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor) {
                return response()->json([
                    'role' => $user->role,
                    'tratamientos' => []
                ]);
            }
            $query->where('doctor_id', $doctor->id);
        }

        return response()->json([
            'role' => $user->role,
            'tratamientos' => $query->get()
        ]);
    }

    public function SesionesGet(){
        $user = Auth::user();

        if ($user->role === 'admin') {
            $sesiones = SesionTratamiento::all();
        } 
        else if ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)->first();
            $tratamiento = TratamientoPaciente::where('doctor_id',$doctor->id)->first();
            $sesion = SesionTratamiento::where('tratamiento_id', $tratamiento->id)
                                            ->get();
            
            $sucursalId = session('sucursal_id');

            return response()->json($sucursalId);

        } 
        else {
            $sesiones = collect();
        }

        return response()->json($sesiones);
    }
}
