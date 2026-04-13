<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AntecedenteMedico;
use App\Models\TratamientoPaciente;

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

    
    public function CrearPaciente(Request $request){
        $request->validate([
            'ci' => 'required|string|max:50',
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'lugar_nacimiento' => 'required|string|max:150',
            'direccion' => 'required|string',
            'ocupacion' => 'required|string|max:100',
            'telefono' => 'required|string|max:50',
            'estado' => 'required|string',
            'genero' => 'required|string',

            // 🔥 VALIDACIÓN IMAGEN
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // 🔥 manejar imagen
        $rutaImagen = null;

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('pacientes', 'public');
        }

        $paciente = Paciente::create([
            'ci' => $request->ci,
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'lugar_nacimiento' => $request->lugar_nacimiento,
            'direccion' => $request->direccion,
            'ocupacion' => $request->ocupacion,
            'telefono' => $request->telefono,

            'estado_civil' => $request->estado,
            'sexo' => $request->genero,

            // 🔥 guardar imagen
            'imagen' => $rutaImagen,

            'created_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Paciente registrado correctamente',
            'paciente' => $paciente
        ]);
    }

    public function GetPaciente(Request $request){
        $user = auth()->user();

        if ($user->role === 'admin') {
            $pacientes = Paciente::with('antecedenteMedico')->get();
        } elseif ($user->role === 'doctor') {
            $pacientes = Paciente::with('antecedenteMedico')->where('created_by', $user->id)->get();
        } else {
            $pacientes = collect([]);
        }

        return response()->json($pacientes);
    }

    public function CrearAntecedente(Request $request, $paciente_id){
        $data = $request->all();

        $booleanFields = [
            'anemia','asma','cardiopatias','diabetes','epilepsia','hipertension',
            'tuberculosis','vih','embarazo','en_tratamiento','recibe_tratamiento',
            'hemorragia','fuma','bebe','protesis','cepillo','hilo','enjuague','sangrado'
        ];

        foreach ($booleanFields as $campo) {
            $data[$campo] = ($request->input($campo) === 'Si');
        }

        AntecedenteMedico::updateOrCreate(
            ['paciente_id' => $paciente_id],
            [
                'antecedentes_familiares' => $request->PatologicosFamiliares,
                'otros' => $request->otros,
                'alergias' => $request->alergias,
                'ultima_visita' => $request->ultima_visita,
                'frecuencia' => $request->frecuencia,
            ] + $data
        );

        return response()->json(['success' => true]);
    }

    public function MostrarAntecendes($paciente_id){
        $antecedente = AntecedenteMedico::where('paciente_id', $paciente_id)->first();

        if ($antecedente) {
            return response()->json($antecedente);
        }

        return response()->json(null);
    }


    public function PacientesTratamientos($pacienteId){
        $tratamientos = TratamientoPaciente::where('paciente_id', $pacienteId)
            ->with('categoria')
            ->get();

        return response()->json($tratamientos);
    }

}
