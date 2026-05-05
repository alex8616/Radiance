<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use App\Models\ProductoSucursal;
use App\Models\SesionTratamiento;
use App\Models\CategoriaTratamiento;

class CategoriaController extends Controller
{
    public function index(){
        return view('admin.categorias');
    }

    public function CrearCategoria(Request $request){
        //return response()->json($request);
        try {
            $categoria = CategoriaTratamiento::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Material guardado correctamente',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function GetCategorias(){
        $categorias = CategoriaTratamiento::get();

        return response()->json($categorias);
    }

    
    public function update(Request $request, $id){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        $categoria = CategoriaTratamiento::findOrFail($id);

        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->save();

        return response()->json([
            'success' => true,
            'message' => 'Categoria actualizado correctamente'
        ]);
    }
}
