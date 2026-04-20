<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caja;
use App\Models\MovimientoCaja;
use Carbon\Carbon;

class CajaController extends Controller
{
    public function index(){
        return view('admin.cajas');
    }

    public function FiltrarCajaEfectivo(Request $request){
        $sucursalId = session('sucursal_id');
        $caja = Caja::where('sucursal_id', $sucursalId)->first();
        
        //return response()->json($sucursalId);
        
        $tipoFiltro = $request->input('tipoFiltro');
        $dia = $request->input('dia');
        $mes = $request->input('mes');
        $anio = $request->input('anio');
        $fechaInicio = Carbon::parse($request->input('fechaInicio'))->startOfDay();
        $fechaFin = Carbon::parse($request->input('fechaFin'))->endOfDay();
        
        switch ($tipoFiltro) {
            case 'DiarioCajaEfectivo':
                $movimientoCaja = MovimientoCaja::where('caja_id', $caja->id)
                    ->where('metodo_pago', 'efectivo')
                    ->with('caja')
                    ->with('caja.sucursal')
                    ->with('tratamiento')
                    ->with('tratamiento.paciente')
                    ->with('tratamiento.doctor')
                    ->with('tratamiento.sesiones')
                    ->with('tratamiento.pagos')
                    ->whereDay('created_at', $dia)
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->get();
                
                $IngresoEfectivo = $movimientoCaja->where('tipo','Ingreso')->sum('monto');
                $EgresoEfectivo = $movimientoCaja->where('tipo','Egreso')->sum('monto');
            break;
            case 'MensualidadCajaEfectivo':
                $movimientoCaja = MovimientoCaja::where('caja_id', $caja->id)
                    ->where('metodo_pago', 'efectivo')
                    ->with('caja')
                    ->with('caja.sucursal')
                    ->with('tratamiento')
                    ->with('tratamiento.paciente')
                    ->with('tratamiento.doctor')
                    ->with('tratamiento.sesiones')
                    ->with('tratamiento.pagos')
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->get();
                    
                $IngresoEfectivo = $movimientoCaja->where('tipo','Ingreso')->sum('monto');
                $EgresoEfectivo = $movimientoCaja->where('tipo','Egreso')->sum('monto');
            break;
            case 'RangoCajaEfectivo':
                $movimientoCaja = MovimientoCaja::where('caja_id', $caja->id)
                    ->where('metodo_pago', 'efectivo')
                    ->with('caja')
                    ->with('caja.sucursal')
                    ->with('tratamiento')
                    ->with('tratamiento.paciente')
                    ->with('tratamiento.doctor')
                    ->with('tratamiento.sesiones')
                    ->with('tratamiento.pagos')
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->get();  
                    
                $IngresoEfectivo = $movimientoCaja->where('tipo','Ingreso')->sum('monto');
                $EgresoEfectivo = $movimientoCaja->where('tipo','Egreso')->sum('monto');
            break;
        }

        return response()->json([
            'CajaEfectivo' => $movimientoCaja,
            'IngresoEfectivo' => $IngresoEfectivo,
            'EgresoEfectivo' => $EgresoEfectivo,
        ]);
    }

    public function registrarEgreso(Request $request){
        $sucursalId = session('sucursal_id');
        $caja = Caja::where('sucursal_id', $sucursalId)->first();

        $movimiento = MovimientoCaja::create([
            'caja_id' => $caja->id,
            'tipo' => 'Egreso',
            'categoria' => $request->categoria,
            'monto' => $request->monto,
            'fecha' => now(),
            'descripcion' => $request->descripcion,
            'metodo_pago' => 'efectivo',
        ]);

        $caja->saldo_egreso += $request->monto;
        $caja->saldo_total = $caja->saldo_ingreso - $caja->saldo_egreso;
        $caja->save();

        return response()->json([
            'message' => 'Egreso registrado correctamente',
            'movimiento' => $movimiento,
            'caja' => $caja
        ]);
    }

    public function FiltrarCajaDeposito(Request $request){
        $sucursalId = session('sucursal_id');
        $caja = Caja::where('sucursal_id', $sucursalId)->first();
        
        //return response()->json($sucursalId);
        
        $tipoFiltro = $request->input('tipoFiltro');
        $dia = $request->input('dia');
        $mes = $request->input('mes');
        $anio = $request->input('anio');
        $fechaInicio = Carbon::parse($request->input('fechaInicio'))->startOfDay();
        $fechaFin = Carbon::parse($request->input('fechaFin'))->endOfDay();
        
        switch ($tipoFiltro) {
            case 'DiarioCajaDeposito':
                $movimientoCaja = MovimientoCaja::where('caja_id', $caja->id)
                    ->where('metodo_pago', 'qr')
                    ->with('caja')
                    ->with('caja.sucursal')
                    ->with('tratamiento')
                    ->with('tratamiento.paciente')
                    ->with('tratamiento.doctor')
                    ->with('tratamiento.sesiones')
                    ->with('tratamiento.pagos')
                    ->whereDay('created_at', $dia)
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->get();

                $saldoIngresoQr = $movimientoCaja->sum('monto');
            break;
            case 'MensualidadCajaDeposito':
                $movimientoCaja = MovimientoCaja::where('caja_id', $caja->id)
                    ->where('metodo_pago', 'qr')
                    ->with('caja')
                    ->with('caja.sucursal')
                    ->with('tratamiento')
                    ->with('tratamiento.paciente')
                    ->with('tratamiento.doctor')
                    ->with('tratamiento.sesiones')
                    ->with('tratamiento.pagos')
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->get();
                    
                $saldoIngresoQr = $movimientoCaja->sum('monto');
            break;
            case 'RangoCajaDeposito':
                $movimientoCaja = MovimientoCaja::where('caja_id', $caja->id)
                    ->where('metodo_pago', 'qr')
                    ->with('caja')
                    ->with('caja.sucursal')
                    ->with('tratamiento')
                    ->with('tratamiento.paciente')
                    ->with('tratamiento.doctor')
                    ->with('tratamiento.sesiones')
                    ->with('tratamiento.pagos')
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->get(); 

                $saldoIngresoQr = $movimientoCaja->sum('monto');
            break;
        }

        return response()->json([
            'CajaDeposito' => $movimientoCaja,
            'IngresoDeposito' => $saldoIngresoQr,
        ]);
    }
    
    public function FiltrarCajaTarjeta(Request $request){
        $sucursalId = session('sucursal_id');
        $caja = Caja::where('sucursal_id', $sucursalId)->first();
        
        //return response()->json($sucursalId);
        
        $tipoFiltro = $request->input('tipoFiltro');
        $dia = $request->input('dia');
        $mes = $request->input('mes');
        $anio = $request->input('anio');
        $fechaInicio = Carbon::parse($request->input('fechaInicio'))->startOfDay();
        $fechaFin = Carbon::parse($request->input('fechaFin'))->endOfDay();
        
        switch ($tipoFiltro) {
            case 'DiarioCajaTarjeta':
                $movimientoCaja = MovimientoCaja::where('caja_id', $caja->id)
                    ->where('metodo_pago', 'tarjeta')
                    ->with('caja')
                    ->with('caja.sucursal')
                    ->with('tratamiento')
                    ->with('tratamiento.paciente')
                    ->with('tratamiento.doctor')
                    ->with('tratamiento.sesiones')
                    ->with('tratamiento.pagos')
                    ->whereDay('created_at', $dia)
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->get();

                $saldoIngresoTarjeta = $movimientoCaja->sum('monto');
            break;
            case 'MensualidadCajaTarjeta':
                $movimientoCaja = MovimientoCaja::where('caja_id', $caja->id)
                    ->where('metodo_pago', 'tarjeta')
                    ->with('caja')
                    ->with('caja.sucursal')
                    ->with('tratamiento')
                    ->with('tratamiento.paciente')
                    ->with('tratamiento.doctor')
                    ->with('tratamiento.sesiones')
                    ->with('tratamiento.pagos')
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->get();
                    
                $saldoIngresoTarjeta = $movimientoCaja->sum('monto');
            break;
            case 'RangoCajaTarjeta':
                $movimientoCaja = MovimientoCaja::where('caja_id', $caja->id)
                    ->where('metodo_pago', 'tarjeta')
                    ->with('caja')
                    ->with('caja.sucursal')
                    ->with('tratamiento')
                    ->with('tratamiento.paciente')
                    ->with('tratamiento.doctor')
                    ->with('tratamiento.sesiones')
                    ->with('tratamiento.pagos')
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->get(); 

                $saldoIngresoTarjeta = $movimientoCaja->sum('monto');
            break;
        }

        return response()->json([
            'CajaTarjeta' => $movimientoCaja,
            'IngresoTarjeta' => $saldoIngresoTarjeta,
        ]);
    }

    public function CajaAdmin(){
        return view('admin.AdminCaja');
    }

    public function FiltrarCajaEfectivoAdmin(Request $request){
        $sucursalId = $request->input('sucursal_id');
        $tipoFiltro = $request->input('tipoFiltro');
        $dia = $request->input('dia');   // corregido
        $mes = $request->input('mes');
        $anio = $request->input('anio');
        $fechaInicio = Carbon::parse($request->input('fechaInicio'))->startOfDay();
        $fechaFin = Carbon::parse($request->input('fechaFin'))->endOfDay();

        // Base query
        $query = MovimientoCaja::where('metodo_pago', 'efectivo')
            ->with('caja.sucursal')
            ->with('tratamiento.paciente')
            ->with('tratamiento.doctor')
            ->with('tratamiento.sesiones')
            ->with('tratamiento.pagos');

        // Filtros según tipo
        switch ($tipoFiltro) {
            case 'DiarioCajaEfectivo':
                $query->whereDay('created_at', $dia)
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio);
                break;

            case 'MensualidadCajaEfectivo':
                $query->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio);
                break;

            case 'RangoCajaEfectivo':
                $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
                break;
        }

        // Filtrar por sucursal si no es "Todos"
        if ($sucursalId !== "Todos") {
            $query->whereHas('caja', function ($q) use ($sucursalId) {
                $q->where('sucursal_id', $sucursalId);
            });
        }

        // Ejecutar consulta
        $movimientoCaja = $query->get();

        // Calcular totales
        $IngresoEfectivo = $movimientoCaja->where('tipo','Ingreso')->sum('monto');
        $EgresoEfectivo = $movimientoCaja->where('tipo','Egreso')->sum('monto');

        return response()->json([
            'CajaEfectivo' => $movimientoCaja,
            'IngresoEfectivo' => $IngresoEfectivo,
            'EgresoEfectivo' => $EgresoEfectivo,
        ]);
    }

    public function FiltrarCajaDepositoAdmin(Request $request){
        $sucursalId = $request->input('sucursal_id_Deposito');
        $tipoFiltro = $request->input('tipoFiltro');
        $dia = $request->input('dia');   // corregido
        $mes = $request->input('mes');
        $anio = $request->input('anio');
        $fechaInicio = Carbon::parse($request->input('fechaInicio'))->startOfDay();
        $fechaFin = Carbon::parse($request->input('fechaFin'))->endOfDay();

        // Base query
        $query = MovimientoCaja::where('metodo_pago', 'qr')
            ->with('caja.sucursal')
            ->with('tratamiento.paciente')
            ->with('tratamiento.doctor')
            ->with('tratamiento.sesiones')
            ->with('tratamiento.pagos');

        // Filtros según tipo
        switch ($tipoFiltro) {
            case 'DiarioCajaDeposito':
                $query->whereDay('created_at', $dia)
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio);
                break;

            case 'MensualidadCajaDeposito':
                $query->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio);
                break;

            case 'RangoCajaDeposito':
                $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
                break;
        }

        // Filtrar por sucursal si no es "Todos"
        if ($sucursalId !== "Todos") {
            $query->whereHas('caja', function ($q) use ($sucursalId) {
                $q->where('sucursal_id', $sucursalId);
            });
        }

        // Ejecutar consulta
        $movimientoCaja = $query->get();

        // Calcular totales
        $IngresoDeposito = $movimientoCaja->where('tipo','Ingreso')->sum('monto');
        $EgresoDeposito = $movimientoCaja->where('tipo','Egreso')->sum('monto');

        return response()->json([
            'CajaDeposito' => $movimientoCaja,
            'IngresoDeposito' => $IngresoDeposito,
            'EgresoDeposito' => $EgresoDeposito,
        ]);
    }

    public function FiltrarCajaTarjetaAdmin(Request $request){
        $sucursalId = $request->input('sucursal_id_Tarjeta');
        $tipoFiltro = $request->input('tipoFiltro');
        $dia = $request->input('dia');   // corregido
        $mes = $request->input('mes');
        $anio = $request->input('anio');
        $fechaInicio = Carbon::parse($request->input('fechaInicio'))->startOfDay();
        $fechaFin = Carbon::parse($request->input('fechaFin'))->endOfDay();

        // Base query
        $query = MovimientoCaja::where('metodo_pago', 'tarjeta')
            ->with('caja.sucursal')
            ->with('tratamiento.paciente')
            ->with('tratamiento.doctor')
            ->with('tratamiento.sesiones')
            ->with('tratamiento.pagos');

        // Filtros según tipo
        switch ($tipoFiltro) {
            case 'DiarioCajaTarjeta':
                $query->whereDay('created_at', $dia)
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio);
                break;

            case 'MensualidadCajaTarjeta':
                $query->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio);
                break;

            case 'RangoCajaTarjeta':
                $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
                break;
        }

        // Filtrar por sucursal si no es "Todos"
        if ($sucursalId !== "Todos") {
            $query->whereHas('caja', function ($q) use ($sucursalId) {
                $q->where('sucursal_id', $sucursalId);
            });
        }

        // Ejecutar consulta
        $movimientoCaja = $query->get();

        // Calcular totales
        $IngresoTarjeta = $movimientoCaja->where('tipo','Ingreso')->sum('monto');
        $EgresoTarjeta = $movimientoCaja->where('tipo','Egreso')->sum('monto');

        return response()->json([
            'CajaTarjeta' => $movimientoCaja,
            'IngresoTarjeta' => $IngresoTarjeta,
            'EgresoTarjeta' => $EgresoTarjeta,
        ]);
    }

    


}