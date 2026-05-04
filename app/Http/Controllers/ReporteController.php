<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\MovimientoCaja;
use Carbon\CarbonPeriod;
use App\Models\Caja;
use App\Models\Sucursal;
use App\Models\TratamientoPaciente;
use App\Models\SesionTratamiento;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index(){
        return view('admin.reportes');
    }
    
    public function generarSucursalesPDF(Request $request){
        //return response()->json($request);

        try {
            // Validar datos
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'sucursal_id' => 'nullable|exists:sucursales,id'
            ]);
            
            // Obtener datos del reporte
            $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
            $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();
            $sucursalId = $request->sucursal_id;
            
            // Obtener las cajas según la sucursal
            $cajasQuery = Caja::with('sucursal');
            if ($sucursalId) {
                $cajasQuery->where('sucursal_id', $sucursalId);
            }
            $cajas = $cajasQuery->get();
            
            // Consultar movimientos (ingresos)
            $ingresosQuery = MovimientoCaja::where('tipo', 'Ingreso')
                ->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            
            if ($sucursalId) {
                $ingresosQuery->whereHas('caja', function($query) use ($sucursalId) {
                    $query->where('sucursal_id', $sucursalId);
                });
            }
            $ingresos = $ingresosQuery->get();
            $totalIngresos = $ingresos->sum('monto');
            
            // Total ingresos por método de pago
            $totalIngresosEfectivo = $ingresos->where('metodo_pago', 'efectivo')->sum('monto');
            $totalIngresosQr = $ingresos->where('metodo_pago', 'qr')->sum('monto');
            $totalIngresosTarjeta = $ingresos->where('metodo_pago', 'tarjeta')->sum('monto');
            
            // Consultar movimientos (egresos)
            $egresosQuery = MovimientoCaja::where('tipo', 'Egreso')
                ->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            
            if ($sucursalId) {
                $egresosQuery->whereHas('caja', function($query) use ($sucursalId) {
                    $query->where('sucursal_id', $sucursalId);
                });
            }
            $egresos = $egresosQuery->get();
            $totalEgresos = $egresos->sum('monto');
            
            // Calcular balance
            $balance = $totalIngresos - $totalEgresos;
            
            // Obtener nombre de sucursal
            $sucursalNombre = 'Todas las sucursales';
            if ($sucursalId) {
                $sucursal = Sucursal::find($sucursalId);
                $sucursalNombre = $sucursal ? $sucursal->nombre : 'No especificada';
            }
            
            // Datos adicionales para gráficos - Ingresos por día y categoría
            $ingresosPorDia = $ingresosQuery->selectRaw('DATE(fecha) as fecha, SUM(monto) as total')
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();
                
            $egresosPorDia = $egresosQuery->selectRaw('DATE(fecha) as fecha, SUM(monto) as total')
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();
            
            // Ingresos por categoría
            $ingresosPorCategoria = $ingresos->groupBy('categoria')->map(function($item) {
                return $item->sum('monto');
            });
            
            // Egresos por categoría
            $egresosPorCategoria = $egresos->groupBy('categoria')->map(function($item) {
                return $item->sum('monto');
            });
            
            // Resumen por caja
            $resumenPorCaja = [];
            foreach ($cajas as $caja) {
                $ingresosCaja = $ingresos->where('caja_id', $caja->id)->sum('monto');
                $egresosCaja = $egresos->where('caja_id', $caja->id)->sum('monto');
                $resumenPorCaja[] = [
                    'nombre' => $caja->nombre,
                    'sucursal' => $caja->sucursal->nombre ?? 'N/A',
                    'ingresos' => $ingresosCaja,
                    'egresos' => $egresosCaja,
                    'balance' => $ingresosCaja - $egresosCaja,
                    'saldo_total' => $caja->saldo_total
                ];
            }
            
            // Preparar datos para la vista PDF
            $data = [
                'fecha_inicio' => $fechaInicio->format('d/m/Y'),
                'fecha_fin' => $fechaFin->format('d/m/Y'),
                'sucursal_nombre' => $sucursalNombre,
                'total_ingresos' => $totalIngresos,
                'total_egresos' => $totalEgresos,
                'balance' => $balance,
                'total_ingresos_efectivo' => $totalIngresosEfectivo,
                'total_ingresos_qr' => $totalIngresosQr,
                'total_ingresos_tarjeta' => $totalIngresosTarjeta,
                'ingresos' => $ingresos,
                'egresos' => $egresos,
                'ingresos_por_dia' => $ingresosPorDia,
                'egresos_por_dia' => $egresosPorDia,
                'ingresos_por_categoria' => $ingresosPorCategoria,
                'egresos_por_categoria' => $egresosPorCategoria,
                'resumen_por_caja' => $resumenPorCaja,
                'cajas' => $cajas,
                'fecha_generacion' => Carbon::now()->format('d/m/Y H:i:s')
            ];
            
            // Generar PDF
            $pdf = PDF::loadView('pdf.reporte-financiero', $data);
            $pdf->setPaper('a4', 'portrait');
            
            // Descargar PDF
            return $pdf->download('reporte_financiero_' . date('Ymd_His') . '.pdf');
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generarTratamientosSesionesPDF(Request $request){
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $accion = $request->accion;
        $sucursalId = $request->sucursal_id;

        if ($accion === 'Sesion') {
            $datos = SesionTratamiento::with(['productos','productos.producto','pagos'])
                ->whereBetween('fecha_atencion', [$fechaInicio, $fechaFin])
                ->when($sucursalId, function($query) use ($sucursalId) {
                    $query->where('sucursal_id', $sucursalId);
                })
                ->get();

        } elseif ($accion === 'Tratamiento') {

            $datos = TratamientoPaciente::with([
                    'paciente',
                    'doctor',
                    'sucursal',
                    'categoria',
                    'pagos',
                ])
                ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                ->when($sucursalId, function($query) use ($sucursalId) {
                    $query->where('sucursal_id', $sucursalId);
                })
                ->get();

        } else {

            $datos = TratamientoPaciente::with([
                    'paciente',
                    'doctor',
                    'sucursal',
                    'categoria',
                    'sesiones',
                    'sesiones.pagos',
                    'sesiones.productos',
                    'sesiones.productos.producto',
                    'pagos',
                ])
                ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                ->when($sucursalId, function($query) use ($sucursalId) {
                    $query->where('sucursal_id', $sucursalId);
                })
                ->get();
        }

        //return response()->json($datos);
        
        // 🔥 AQUÍ GENERAS TU PDF (ejemplo con DOMPDF)
        $pdf = \PDF::loadView('pdf.reporte-tratamientos-sesiones', compact('datos', 'fechaInicio', 'fechaFin', 'accion' ));

        return $pdf->stream('tratamientos.pdf');
    }
}

