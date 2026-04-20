<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\TratamientoPaciente;
use App\Models\MovimientoCaja;
use App\Models\Caja;

use Illuminate\Support\Str;

class PagoController extends Controller
{
    public function guardarAdelanto(Request $request)
    {
        $request->validate([
            'tratamiento_id' => 'required|exists:tratamientos_paciente,id',
            'pagos' => 'required|array|min:1',
            'pagos.*.monto' => 'required|numeric|min:0.01',
            'pagos.*.metodo' => 'required|in:efectivo,qr,tarjeta'
        ]);

        $grupo = \Illuminate\Support\Str::uuid();

        $tratamiento = TratamientoPaciente::findOrFail($request->tratamiento_id);

        // ✅ Verificar que la sucursal tenga caja
        $sucursalId = session('sucursal_id');
        $caja = Caja::where('sucursal_id', $sucursalId)->first();

        if (!$caja) {
            $caja = $tratamiento->sucursal->cajas()->create([
                'nombre' => 'Caja Principal',
                'descripcion' => 'Caja creada automáticamente',
                'saldo_ingreso' => 0,
                'saldo_egreso' => 0,
                'saldo_total' => 0,
                'saldo_ingresoQr' => 0,
                'saldo_ingresoTarjeta' => 0,
            ]);
        }

        foreach ($request->pagos as $pago) {
            $metodo = $pago['metodo'];

            // Guardar pago
            Pago::create([
                'tratamiento_id' => $tratamiento->id,
                'sesion_id' => null,
                'paciente_id' => $tratamiento->paciente_id,
                'monto' => $pago['monto'],
                'metodo_pago' => $metodo,
                'fecha' => now(),
                'grupo_pago' => $grupo,
            ]);

            // Guardar movimiento de caja
            MovimientoCaja::create([
                'caja_id' => $caja->id,
                'tipo' => 'Ingreso',
                'categoria' => 'Adelanto',
                'monto' => $pago['monto'],
                'fecha' => now(),
                'descripcion' => 'Adelanto de tratamiento ID '.$tratamiento->id.' paciente '.$tratamiento->paciente->nombre .' '.$tratamiento->paciente->apellido_paterno.' '.$tratamiento->paciente->apellido_materno,
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

        // Recalcular saldo del tratamiento
        $totalPagos = Pago::where('tratamiento_id', $tratamiento->id)->sum('monto');
        $tratamiento->saldo_total = $totalPagos;
        $tratamiento->diferencia_costo = $tratamiento->costo_total - $totalPagos;
        $tratamiento->save();

        return response()->json([
            'message' => 'Adelanto registrado correctamente',
            'paciente_id' => $tratamiento->paciente_id,
            'caja' => $caja
        ]);
    }


}
