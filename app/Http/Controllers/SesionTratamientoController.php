<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesionTratamiento;
use App\Models\TratamientoPaciente;
use Illuminate\Support\Str;
use App\Models\Pago;
use App\Models\MovimientoCaja;
use App\Models\Caja;

class SesionTratamientoController extends Controller
{
    public function CrearSesion(Request $request, $tratamientoId){
        $tratamiento = TratamientoPaciente::findOrFail($tratamientoId);

        $validated = $request->validate([
            'fechaAtencion' => 'required|date',
            'analisis' => 'required|string|max:255',
            'planAccion' => 'required|string',
            'firma' => 'nullable|string|max:255',
            'pagos' => 'nullable|string'
        ]);

        $sesion = SesionTratamiento::create([
            'sucursal_id' => $tratamiento->sucursal_id,
            'tratamiento_id' => $tratamientoId,
            'fecha_atencion' => $validated['fechaAtencion'],
            'analisis' => $validated['analisis'],
            'plan_accion' => $validated['planAccion'],
            'costo' => 0,
            'saldo' => 0,
            'firma' => $validated['firma'] ?? null,
        ]);

        $pagos = json_decode($request->pagos, true);

        if (!empty($pagos)) {
            $grupo = \Illuminate\Support\Str::uuid();
            $sucursalId = session('sucursal_id');
            $caja = Caja::where('sucursal_id', $sucursalId)->first();

            foreach ($pagos as $pago) {
                $metodo = $pago['metodo'];

                // Guardar pago
                Pago::create([
                    'tratamiento_id' => $tratamientoId,
                    'paciente_id' => $tratamiento->paciente_id,
                    'sesion_id' => $sesion->id,
                    'monto' => $pago['monto'],
                    'metodo_pago' => $metodo,
                    'fecha' => now(),
                    'grupo_pago' => $grupo,
                ]);

                // Guardar movimiento de caja
                MovimientoCaja::create([
                    'caja_id' => $caja->id,
                    'tipo' => 'Ingreso',
                    'categoria' => 'PagoTratamiento',
                    'monto' => $pago['monto'],
                    'fecha' => now(),
                    'descripcion' => 'Pago en sesión ID '.$sesion->id.' paciente '.$tratamiento->paciente->nombre .' '.$tratamiento->paciente->apellido_paterno.' '.$tratamiento->paciente->apellido_materno,
                    'tratamiento_id' => $tratamiento->id,
                    'paciente_id' => $tratamiento->paciente_id,
                    'metodo_pago' => $metodo,
                ]);

                // 🔥 Actualizar saldos de la caja según método
                switch ($metodo) {
                    case 'efectivo':
                        $caja->saldo_ingreso += $pago['monto'];
                        $caja->saldo_total = $caja->saldo_ingreso - $caja->saldo_egreso;
                        break;
                    case 'qr':
                        $caja->saldo_ingresoQr += $pago['monto'];
                        break;
                    case 'tarjeta':
                        $caja->saldo_ingresoTarjeta += $pago['monto'];
                        break;
                }

                $caja->save();
            }
        }

        // Recalcular saldo del tratamiento
        $totalPagos = Pago::where('tratamiento_id', $tratamientoId)->sum('monto');
        $tratamiento->saldo_total = $totalPagos;
        $tratamiento->diferencia_costo = $tratamiento->costo_total - $totalPagos;
        $tratamiento->save();

        return response()->json([
            'message' => 'Sesión creada correctamente con pagos registrados',
            'paciente_id' => $tratamiento->paciente_id,
            'caja' => isset($caja) ? $caja : null
        ]);
    }


}
