<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use App\Models\ProductoSucursal;
use App\Models\SesionTratamiento;

class ProductoController extends Controller
{
    public function GetProductos(){

        $sucursalId = session('sucursal_id');
        
        //return response()->json($sucursalId);

        $productos = ProductoSucursal::where('sucursal_id', $sucursalId)
            ->with('producto')
            ->get();

        return response()->json($productos);
    }

    public function CrearProductoSesion(Request $request, $sesionId){
        //return response()->json($request);
        $productos = $request->productos;

        if (!$productos || !is_array($productos)) {
            return response()->json([
                'success' => false,
                'message' => 'No hay productos'
            ], 400);
        }

        DB::beginTransaction();

        try {

            foreach ($productos as $p) {

                // 🔥 ahora validar producto_sucursal_id
                if (!isset($p['producto_sucursal_id'])) {
                    continue;
                }

                // 🔥 obtener precio desde inventario
                $productoSucursal = ProductoSucursal::find($p['producto_sucursal_id']);

                if (!$productoSucursal) {
                    continue;
                }

                DB::table('sesion_producto')->insert([
                    'sesion_id'             => $sesionId,
                    'producto_sucursal_id'  => $p['producto_sucursal_id'], // 🔥 CAMBIO
                    'detalle'               => $p['detalle'] ?? null,
                    'precio'               => $productoSucursal->precio, // 🔥 NUEVO
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);
            }

            DB::commit();

            $tratamientoId = SesionTratamiento::where('id', $sesionId)->value('tratamiento_id');
            return response()->json($tratamientoId);

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
