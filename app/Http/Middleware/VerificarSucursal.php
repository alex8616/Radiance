<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarSucursal
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'doctor') {
            
            // ❌ No tiene sucursal → seleccionar
            if (!session()->has('sucursal_id')) {

                if (!$request->is('seleccionar-sucursal')) {
                    return redirect('/seleccionar-sucursal');
                }
            } 
            // ✅ Tiene sucursal → redirigir a raíz "/"
            else {
                if ($request->is('dashboard')) {
                    return redirect('/');
                }
            }
        }

        return $next($request);
    }
}