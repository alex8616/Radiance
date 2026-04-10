<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarSucursal
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'doctor') {

            if (!session()->has('sucursal_id')) {

                if (!$request->is('seleccionar-sucursal')) {
                    return redirect('/seleccionar-sucursal');
                }
            }
        }

        return $next($request);
    }
}