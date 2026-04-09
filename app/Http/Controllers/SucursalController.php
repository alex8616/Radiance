<?php
namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function index(){
        return view('admin.sucursales');
    }

    public function create(Request $request){
        //return response()->json($request->all());
        $coordenadas = explode(',', $request->ubicacion);

        // Validación básica
        if (count($coordenadas) !== 2) {
            return response()->json([
                'error' => 'Formato de ubicación inválido'
            ], 400);
        }

        $latitud = (float) trim($coordenadas[0]);
        $longitud = (float) trim($coordenadas[1]);

        // Guardar usando el modelo
        $sucursal = Sucursal::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'latitud' => $latitud,
            'longitud' => $longitud
        ]);

        return response()->json([
            'message' => 'Sucursal guardada correctamente',
            'data' => $sucursal
        ]);
    }

    public function GetSucursales(){
        $sucursales = Sucursal::get();
        return response()->json($sucursales);
    }
}