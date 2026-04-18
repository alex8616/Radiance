<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use App\Models\ProductoSucursal;
use App\Models\SesionTratamiento;

class ProductoController extends Controller
{
    public function index(){
        return view('admin.materiales');
    }

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

    public function GetMateriales(){
        $productos = Producto::with('sucursales')->get();
        return response()->json($productos);
    }

    public function update(Request $request, $id){
        // 🔥 Validación básica
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        // 🔥 Buscar material
        $material = Producto::findOrFail($id);

        // 🔥 Actualizar
        $material->nombre = $request->nombre;
        $material->descripcion = $request->descripcion;
        $material->save();

        return response()->json([
            'success' => true,
            'message' => 'Material actualizado correctamente'
        ]);
    }

    public function CrearMaterial(Request $request){
        ///return response()->json($request);
        try {
            // 🔥 Validación
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'sucursales' => 'required|array|min:1',
                'sucursales.*' => 'integer|exists:sucursales,id',
            ]);

            DB::beginTransaction();

            // 🔥 Crear producto
            $material = Producto::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
            ]);

            // 🔥 Preparar datos para pivote (OBLIGATORIO por precio/stock)
            $syncData = [];

            foreach ($validated['sucursales'] as $sucursalId) {
                $syncData[$sucursalId] = [
                    'precio' => 0, // valor por defecto
                    'stock'  => 0  // valor por defecto
                ];
            }

            // 🔥 Relacionar sucursales
            $material->sucursales()->sync($syncData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Material guardado correctamente',
                'material' => $material->load('sucursales')
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function asignarSucursales(Request $request){
        try {

            // 🔥 Validación
            $validated = $request->validate([
                'producto_id' => 'required|exists:productos,id',
                'sucursales' => 'required|array|min:1',
                'sucursales.*' => 'integer|exists:sucursales,id',
            ]);

            DB::beginTransaction();

            $producto = Producto::findOrFail($validated['producto_id']);

            // 🔥 SINCRONIZA (reemplaza las anteriores)
            $producto->sucursales()->sync($validated['sucursales']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sucursales asignadas correctamente',
                'producto' => $producto->load('sucursales')
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al asignar sucursales',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
