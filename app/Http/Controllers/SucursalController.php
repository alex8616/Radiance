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
        $coordenadas = explode(',', $request->ubicacion);

        if (count($coordenadas) !== 2) {
            return response()->json([
                'error' => 'Formato de ubicación inválido'
            ], 400);
        }

        $latitud = (float) trim($coordenadas[0]);
        $longitud = (float) trim($coordenadas[1]);

        // Guardar sucursal
        $sucursal = Sucursal::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'latitud' => $latitud,
            'longitud' => $longitud
        ]);

        // 🔥 Crear caja automáticamente
        $sucursal->cajas()->create([
            'nombre' => 'Caja '. $request->nombre,
            'descripcion' => 'Caja creada automáticamente para la sucursal '. $request->nombre,
            'saldo_inicial' => 0
        ]);

        return response()->json([
            'message' => 'Sucursal guardada correctamente con su caja principal',
            'data' => $sucursal->load('cajas') // devuelve también la caja creada
        ]);
    }


    public function GetSucursales(){
        $sucursales = Sucursal::get();
        return response()->json($sucursales);
    }

    public function GetIdSucursales($idsucursal){
        $sucursal = Sucursal::find($idsucursal);
        if (!$sucursal) {
            return response()->json([
                'error' => 'Sucursal no encontrada'
            ], 404);
        }
        return response()->json($sucursal);
    }

    public function update(Request $request, $id){
        $sucursal = Sucursal::findOrFail($id);

        $sucursal->nombre    = $request->input('nombre');
        $sucursal->direccion = $request->input('direccion');
        $sucursal->telefono  = $request->input('telefono');
        $sucursal->latitud   = $request->input('latitud');
        $sucursal->longitud  = $request->input('longitud');

        $sucursal->save();

        return response()->json([
            'success' => true,
            'message' => 'Sucursal actualizada correctamente',
            'data'    => $sucursal
        ]);
    }


    public function destroy($id){
        $sucursal = Sucursal::findOrFail($id);

        // Validar si tiene doctores o citas
        if ($sucursal->doctores()->count() > 0 || $sucursal->citas()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la sucursal porque tiene doctores o citas asociadas.'
            ], 400);
        }

        $sucursal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sucursal eliminada correctamente'
        ]);
    }


}