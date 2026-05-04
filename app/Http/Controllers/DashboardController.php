<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Pago;
use App\Models\Doctor;
use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Models\TratamientoPaciente;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ========== MÉTRICAS PRINCIPALES ==========
        
        // Total de pacientes activos (con tratamientos)
        $pacientesActivos = TratamientoPaciente::where('estado', '!=', 'completado')
            ->distinct('paciente_id')
            ->count('paciente_id');
        
        // Nuevos pacientes este mes
        $nuevosPacientesMes = Paciente::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        // ========== MÉTRICAS DE CAJA ==========
        
        // Saldo total de todas las cajas
        $saldoTotalCajas = Caja::sum('saldo_total');
        
        // Ingresos del mes (de la tabla Caja)
        $ingresosMes = Caja::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('saldo_ingreso');
        
        // Egresos del mes
        $egresosMes = Caja::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('saldo_egreso');
        
        // Ingresos por QR este mes
        $ingresosQRMes = Caja::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('saldo_ingresoQr');
        
        // Ingresos por Tarjeta este mes
        $ingresosTarjetaMes = Caja::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('saldo_ingresoTarjeta');
        
        // Ingresos en efectivo (resta del total)
        $ingresosEfectivoMes = $ingresosMes - ($ingresosQRMes + $ingresosTarjetaMes);
        
        // Ingresos del mes anterior para comparación (desde Pagos)
        $ingresosMesAnterior = Pago::whereMonth('fecha', Carbon::now()->subMonth()->month)
            ->whereYear('fecha', Carbon::now()->subMonth()->year)
            ->sum('monto');
        
        // Porcentaje de cambio en ingresos
        $ingresosCambio = $ingresosMesAnterior > 0 
            ? round((($ingresosMes - $ingresosMesAnterior) / $ingresosMesAnterior) * 100, 1)
            : ($ingresosMes > 0 ? 100 : 0);
        
        // ========== MOVIMIENTOS DE CAJA RECIENTES ==========
        
        $movimientosRecientes = MovimientoCaja::with(['caja', 'paciente', 'tratamiento'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($movimiento) {
                return (object) [
                    'tipo' => $movimiento->tipo ?? 'N/A',  // Cambiado de tipo_movimiento a tipo
                    'monto' => $movimiento->monto ?? 0,
                    'concepto' => $movimiento->descripcion ?? 'Sin concepto',  // Cambiado de concepto a descripcion
                    'categoria' => $movimiento->categoria ?? 'N/A',
                    'metodo_pago' => $movimiento->metodo_pago ?? 'N/A',
                    'fecha' => $movimiento->fecha ? Carbon::parse($movimiento->fecha)->format('d/m/Y H:i') : Carbon::parse($movimiento->created_at)->format('d/m/Y H:i'),
                    'paciente' => $movimiento->paciente ? $movimiento->paciente->nombre . ' ' . $movimiento->paciente->apellido_paterno : 'N/A',
                    'caja' => $movimiento->caja ? $movimiento->caja->nombre : 'N/A'
                ];
            });
        
        // Resumen de movimientos por tipo (este mes) - Cambiado tipo_movimiento a tipo
        $ingresosMovimientos = MovimientoCaja::where('tipo', 'ingreso')
            ->whereMonth('fecha', Carbon::now()->month)
            ->whereYear('fecha', Carbon::now()->year)
            ->sum('monto');
        
        $egresosMovimientos = MovimientoCaja::where('tipo', 'Egreso')
            ->whereMonth('fecha', Carbon::now()->month)
            ->whereYear('fecha', Carbon::now()->year)
            ->sum('monto');
        
        // Balance del mes (ingresos - egresos)
        $balanceMes = $ingresosMovimientos - $egresosMovimientos;
        
        // ========== TRATAMIENTOS ==========
        
        // Tratamientos pendientes (no completados)
        $tratamientosPendientes = TratamientoPaciente::where('estado', '!=', 'completado')
            ->where('estado', '!=', 'cancelado')
            ->count();
        
        // Tratamientos con saldo pendiente
        $tratamientosConDeuda = TratamientoPaciente::where('saldo_total', '>', 0)
            ->where('estado', '!=', 'cancelado')
            ->count();
        
        // ========== ÚLTIMOS PACIENTES REGISTRADOS ==========
        
        $ultimosPacientes = Paciente::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($paciente) {
                return (object) [
                    'nombre' => $paciente->nombre . ' ' . $paciente->apellido_paterno,
                    'telefono' => $paciente->telefono ?? 'Sin teléfono',
                    'fecha_registro' => Carbon::parse($paciente->created_at)->format('d/m/Y'),
                    'ci' => $paciente->ci
                ];
            });
        
        // ========== TRATAMIENTOS MÁS REALIZADOS ==========
        
        $clasesColores = ['bg-primary', 'bg-success', 'bg-warning', 'bg-info', 'bg-danger'];
        
        $tratamientosRealizados = TratamientoPaciente::select('categoria_id')
            ->with('categoria')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get()
            ->groupBy('categoria_id')
            ->map(function($grupo, $categoriaId) use ($clasesColores) {
                static $idx = 0;
                $categoria = $grupo->first()->categoria;
                $total = $grupo->count();
                $clase = $clasesColores[$idx % count($clasesColores)];
                $idx++;
                return (object) [
                    'nombre' => $categoria ? $categoria->nombre : 'Sin categoría',
                    'total' => $total,
                    'porcentaje' => 0,
                    'clase' => $clase
                ];
            })
            ->sortByDesc('total')
            ->take(5);
        
        // Calcular porcentajes
        $totalTratamientos = $tratamientosRealizados->sum('total');
        foreach ($tratamientosRealizados as $tratamiento) {
            $tratamiento->porcentaje = $totalTratamientos > 0 
                ? round(($tratamiento->total / $totalTratamientos) * 100, 1)
                : 0;
        }
        
        // ========== PRÓXIMOS CUMPLEAÑOS ==========
        
        $proximosCumpleanos = Paciente::whereRaw('DAYOFYEAR(fecha_nacimiento) BETWEEN ? AND ?', [
            Carbon::now()->dayOfYear,
            Carbon::now()->addDays(7)->dayOfYear
        ])
        ->limit(3)
        ->get()
        ->map(function($paciente) {
            return (object) [
                'nombre' => $paciente->nombre . ' ' . $paciente->apellido_paterno,
                'fecha' => Carbon::parse($paciente->fecha_nacimiento)->format('d/m'),
                'telefono' => $paciente->telefono
            ];
        });
        
        // ========== CUENTAS POR COBRAR ==========
        
        // Total de saldos pendientes (deuda total)
        $cuentasPorCobrar = TratamientoPaciente::where('saldo_total', '>', 0)
            ->where('estado', '!=', 'cancelado')
            ->sum('saldo_total');
        
        // Pacientes con deuda
        $pacientesConDeuda = TratamientoPaciente::where('saldo_total', '>', 0)
            ->where('estado', '!=', 'cancelado')
            ->distinct('paciente_id')
            ->count('paciente_id');
        
        // Tratamientos con deuda mayor a 30 días
        $tratamientosVencidos = TratamientoPaciente::where('saldo_total', '>', 0)
            ->where('fecha_inicio', '<', Carbon::now()->subDays(30))
            ->count();
        
        // ========== ESTADÍSTICAS ADICIONALES ==========
        
        // Ticket promedio por paciente
        $totalIngresos = Pago::sum('monto');
        $totalPacientes = Paciente::count();
        $ticketPromedio = $totalPacientes > 0 
            ? '$' . number_format($totalIngresos / $totalPacientes, 0, ',', '.')
            : '$0';
        
        // Tasa de retención (pacientes con más de un tratamiento)
        $pacientesConMultiplesTratamientos = TratamientoPaciente::select('paciente_id')
            ->groupBy('paciente_id')
            ->havingRaw('COUNT(*) > 1')
            ->distinct('paciente_id')
            ->count('paciente_id');
        
        $retencion = $totalPacientes > 0 
            ? round(($pacientesConMultiplesTratamientos / $totalPacientes) * 100)
            : 0;
        
        // Total de doctores
        $totalDoctores = Doctor::count();
        
        // Ingreso promedio por tratamiento completado
        $ingresoPromedioTratamiento = TratamientoPaciente::where('estado', 'completado')
            ->avg('costo_total') ?? 0;
        
        // ========== DATOS PARA GRÁFICOS ==========
        
        // Ingresos por mes (últimos 6 meses) desde MovimientoCaja
        $ingresosPorMes = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $ingreso = MovimientoCaja::where('tipo', 'Ingreso')
                ->whereMonth('fecha', $fecha->month)
                ->whereYear('fecha', $fecha->year)
                ->sum('monto');
            
            $ingresosPorMes[] = [
                'mes' => $fecha->locale('es')->isoFormat('MMM'),
                'monto' => (float) $ingreso // 🔥 FIX
            ];
        }
        
        // Tratamientos por estado
        $tratamientosPorEstado = [
            'pendiente' => TratamientoPaciente::where('estado', 'pendiente')->count(),
            'en_proceso' => TratamientoPaciente::where('estado', 'en_proceso')->count(),
            'completado' => TratamientoPaciente::where('estado', 'completado')->count(),
            'cancelado' => TratamientoPaciente::where('estado', 'cancelado')->count(),
        ];
        
        // Métodos de pago más usados desde MovimientoCaja
        $metodosPago = MovimientoCaja::select('metodo_pago')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('metodo_pago')
            ->groupBy('metodo_pago')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get();
        
        // ========== INFORMACIÓN DE SUCURSALES ==========
        
        // Total de cajas registradas
        $totalCajas = Caja::count();
        
        // Sucursal con mayor saldo
        $sucursalMayorSaldo = Caja::with('sucursal')
            ->orderBy('saldo_total', 'desc')
            ->first();
        
        // ========== COMPACTAR TODOS LOS DATOS ==========
        
        /*return response()->json([
            'pacientesActivos' => $pacientesActivos,
            'nuevosPacientesMes' => $nuevosPacientesMes,
            'ingresosMes' => $ingresosMes,
            'egresosMes' => $egresosMes,
            'ingresosCambio' => $ingresosCambio,
            'ingresosQRMes' => $ingresosQRMes,
            'ingresosTarjetaMes' => $ingresosTarjetaMes,
            'ingresosEfectivoMes' => $ingresosEfectivoMes,
            'saldoTotalCajas' => $saldoTotalCajas,
            'movimientosRecientes' => $movimientosRecientes,
            'ingresosMovimientos' => $ingresosMovimientos,
            'egresosMovimientos' => $egresosMovimientos,
            'balanceMes' => $balanceMes,
            'tratamientosPendientes' => $tratamientosPendientes,
            'tratamientosConDeuda' => $tratamientosConDeuda,
            'ultimosPacientes' => $ultimosPacientes,
            'tratamientosRealizados' => $tratamientosRealizados,
            'proximosCumpleanos' => $proximosCumpleanos,
            'cuentasPorCobrar' => $cuentasPorCobrar,
            'pacientesConDeuda' => $pacientesConDeuda,
            'tratamientosVencidos' => $tratamientosVencidos,
            'ticketPromedio' => $ticketPromedio,
            'retencion' => $retencion,
            'totalDoctores' => $totalDoctores,
            'ingresoPromedioTratamiento' => $ingresoPromedioTratamiento,
            'ingresosPorMes' => $ingresosPorMes,
            'tratamientosPorEstado' => $tratamientosPorEstado,
            'metodosPago' => $metodosPago,
            'totalCajas' => $totalCajas,
            'sucursalMayorSaldo' => $sucursalMayorSaldo,
        ]);*/

        return view('welcome', compact(
            'pacientesActivos',
            'nuevosPacientesMes',
            'ingresosMes',
            'egresosMes',
            'ingresosCambio',
            'ingresosQRMes',
            'ingresosTarjetaMes',
            'ingresosEfectivoMes',
            'saldoTotalCajas',
            'movimientosRecientes',
            'ingresosMovimientos',
            'egresosMovimientos',
            'balanceMes',
            'tratamientosPendientes',
            'tratamientosConDeuda',
            'ultimosPacientes',
            'tratamientosRealizados',
            'proximosCumpleanos',
            'cuentasPorCobrar',
            'pacientesConDeuda',
            'tratamientosVencidos',
            'ticketPromedio',
            'retencion',
            'totalDoctores',
            'ingresoPromedioTratamiento',
            'ingresosPorMes',
            'tratamientosPorEstado',
            'metodosPago',
            'totalCajas',
            'sucursalMayorSaldo'
        ));
    }
}