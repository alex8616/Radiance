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

    public function CrearTratamiento(Request $request, $pacienteId)
    {
        try {

            $doctorId = Doctor::where('user_id', auth()->id())->value('id');
            $sucursalId = session('sucursal_id');

            if (!$doctorId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Doctor no encontrado.'
                ], 400);
            }

            if (!$sucursalId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe seleccionar una sucursal antes de registrar un tratamiento.'
                ], 400);
            }

            $validated = $request->validate(
                [
                    'categoria_id'       => 'required|exists:categorias_tratamientos,id',
                    'descripcion'        => 'nullable|string',
                    'fecha_inicio'       => 'required|date',
                    'fecha_fin_estimada' => 'nullable|date',
                    'costo_total'        => 'required|numeric|min:0',
                ],
                [
                    'categoria_id.required' => 'Debe seleccionar una categoría.',
                    'categoria_id.exists'   => 'La categoría seleccionada no existe.',
                    'fecha_inicio.required' => 'Debe ingresar la fecha de inicio.',
                    'fecha_inicio.date'     => 'La fecha de inicio no es válida.',
                    'fecha_fin_estimada.date' => 'La fecha estimada no es válida.',
                    'costo_total.required'  => 'Debe ingresar el costo total.',
                    'costo_total.numeric'   => 'El costo total debe ser numérico.',
                    'costo_total.min'       => 'El costo total no puede ser negativo.',
                ]
            );

            $tratamiento = TratamientoPaciente::create([
                'paciente_id'        => $pacienteId,
                'doctor_id'          => $doctorId,
                'sucursal_id'        => $sucursalId,
                'categoria_id'       => $validated['categoria_id'],
                'descripcion'        => $validated['descripcion'] ?? null,
                'fecha_inicio'       => $validated['fecha_inicio'],
                'fecha_fin_estimada' => $validated['fecha_fin_estimada'] ?? null,
                'costo_total'        => $validated['costo_total'],
                'estado'             => 'activo',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tratamiento registrado correctamente.',
                'tratamiento' => $tratamiento,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            throw $e; // Laravel enviará automáticamente los errores de validación

        } catch (\Exception $e) {

            \Log::error('Error al crear tratamiento', [
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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
            $query->where('doctor_id', $doctor->id)->with([
                'paciente',
                'categoria',
            ]);
        }

        $tratamientos = $query->with([
                'paciente',
                'categoria',
            ])->get();

        return response()->json([
            'role' => $user->role,
            'tratamientos' => $tratamientos
        ]);
    }

    public function SesionesGet(){
        $user = Auth::user();

        if ($user->role === 'admin') {
            $sesiones = SesionTratamiento::with('tratamiento')
                                        ->with('tratamiento.paciente')
                                        ->get();
        } 
        else if ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)->first();
            $sucursalId = session('sucursal_id');

            $tratamientos = TratamientoPaciente::where('doctor_id', $doctor->id)->get();

            $sesiones = SesionTratamiento::whereIn('tratamiento_id', $tratamientos->pluck('id'))
                                        ->where('sucursal_id', $sucursalId)
                                        ->with('tratamiento')
                                        ->with('tratamiento.paciente')
                                        ->get();

            return response()->json($sesiones);
        }
        else {
            $sesiones = collect();
        }

        return response()->json($sesiones);
    }

    public function TratamientosSelect($tratamientoId){
        $tratamiento = TratamientoPaciente::with([
                                            'paciente',
                                            'doctor',
                                            'sucursal',
                                            'categoria',
                                            'sesiones',
                                            'pagos',
                                            'sesiones.productos.producto'
                                        ])->find($tratamientoId);

        if (!$tratamiento) {
            return response()->json(['error' => 'Tratamiento no encontrado'], 404);
        }

        return response()->json($tratamiento);
    }

    public function concluir($id){
        $tratamiento = TratamientoPaciente::findOrFail($id);
        if ($tratamiento->costo_total == $tratamiento->saldo_total) {
            $tratamiento->estado = 'finalizado';
            $tratamiento->fecha_fin_estimada = now();
            $tratamiento->save();

            return response()->json([
                'success' => true,
                'message' => 'Tratamiento concluido correctamente.'
            ]);
        }

        return response()->json($tratamiento->paciente_id);
    }
}
