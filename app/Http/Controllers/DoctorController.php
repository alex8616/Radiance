<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index(){
        return view('admin.usuarios');
    }

    public function GetUsuarios(){
        $usuarios = User::get();
        return response()->json($usuarios);
    }

    public function create(Request $request){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:50',
            'celular' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,doctor',
            'password' => 'required|confirmed|min:6',
            'especialidad' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->nombre,
            'ci' => $request->documento,
            'celular' => $request->celular,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role === 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
                'nombre' => $request->nombre,
                'especialidad' => $request->especialidad,
                'telefono' => $request->celular,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente',
            'data' => $user
        ]);
    }

    public function asignarSucursal(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'sucursal_id' => 'required|exists:sucursales,id',
        ]);

        // 🔍 Buscar doctor por user_id
        $doctor = Doctor::where('user_id', $request->user_id)->first();

        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no es un doctor'
            ], 404);
        }

        // 🔥 Evitar duplicados
        if ($doctor->sucursales()->where('sucursal_id', $request->sucursal_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'La sucursal ya está asignada a este doctor'
            ], 400);
        }

        // ✅ Asignar sucursal
        $doctor->sucursales()->attach($request->sucursal_id);

        return response()->json([
            'success' => true,
            'message' => 'Sucursal asignada correctamente'
        ]);
    }
    
    public function getSucursalesAsignadas($id){
        $doctor = Doctor::where('user_id', $id)
            ->with('sucursales') // 🔥 carga relación
            ->first();

        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $doctor->sucursales
        ]);
    }

    public function sucursalesDoctor(){
        $doctor = Doctor::where('user_id', auth()->id())
            ->with('sucursales')
            ->first();

        return response()->json([
            'success' => true,
            'data' => $doctor ? $doctor->sucursales : []
        ]);
    }

    public function seleccionarSucursal(Request $request){
        $request->validate([
            'sucursal_id' => 'required|exists:sucursales,id'
        ]);

        session(['sucursal_id' => $request->sucursal_id]);

        return response()->json([
            'success' => true
        ]);
    }
}
