<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function GetProductos(){
        $productos = Producto::all();
        return response()->json($productos);
    }

    public function CrearProductoSesion(Request $request, $sesionId){
        //return response()->json($request);
        $productos = $request->productos;

        // 🔴 validación básica
        if (!$productos || !is_array($productos)) {
            return response()->json([
                'success' => false,
                'message' => 'No hay productos'
            ], 400);
        }

        DB::beginTransaction();

        try {

            foreach ($productos as $p) {

                // 🔵 seguridad básica por si viene incompleto
                if (!isset($p['producto_id'])) {
                    continue;
                }

                DB::table('sesion_producto')->insert([
                    'sesion_id'   => $sesionId,
                    'producto_id' => $p['producto_id'],
                    'detalle'     => $p['detalle'] ?? null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Productos agregados correctamente'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
