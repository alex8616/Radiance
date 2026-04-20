<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\MovimientoCaja;
use Carbon\CarbonPeriod;

class ReporteController extends Controller
{
    public function index(){
        return view('admin.reportes');
    }
    
    public function graficoIngresos(Request $request){
        $fechaInicio = $request->fechaInicio
            ? Carbon::parse($request->fechaInicio)
            : now()->startOfMonth();

        $fechaFin = $request->fechaFin
            ? Carbon::parse($request->fechaFin)
            : now();

        $sucursalId = $request->sucursal_id;

        $query = MovimientoCaja::query();

        if ($sucursalId) {
            $query->whereHas('caja', function ($q) use ($sucursalId) {
                $q->where('sucursal_id', $sucursalId);
            });
        }

        $movimientos = $query
            ->whereBetween('fecha', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->get();

        // 🔥 agrupar por fecha
        $movimientosPorFecha = $movimientos->groupBy('fecha');

        $fechas = [];
        $ingresos = [];
        $egresos = [];

        $periodo = CarbonPeriod::create($fechaInicio, $fechaFin);

        foreach ($periodo as $fecha) {

            $f = $fecha->format('Y-m-d');

            $fechas[] = $f;

            $dataDia = $movimientosPorFecha->get($f, collect());

            $ingresos[] = $dataDia
                ->where('tipo', 'Ingreso')
                ->sum('monto');

            $egresos[] = $dataDia
                ->where('tipo', 'Egreso')
                ->sum('monto');
        }

        return response()->json([
            'fechas' => $fechas,
            'ingresos' => $ingresos,
            'egresos' => $egresos
        ]);
    }
}
