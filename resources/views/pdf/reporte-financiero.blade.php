<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Financiero</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
        }
        .info {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        .resumen {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .card {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            width: 30%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card.ingresos {
            border-left: 4px solid #28a745;
        }
        .card.egresos {
            border-left: 4px solid #dc3545;
        }
        .card.balance {
            border-left: 4px solid #007bff;
        }
        .card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .card .monto {
            font-size: 20px;
            font-weight: bold;
        }
        .metodos-pago {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .metodo-item {
            text-align: center;
        }
        .metodo-item .label {
            font-size: 11px;
            color: #666;
        }
        .metodo-item .monto {
            font-size: 16px;
            font-weight: bold;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .table .text-success {
            color: #28a745;
        }
        .table .text-danger {
            color: #dc3545;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-info {
            color: #007bff;
        }
        .page-break {
            page-break-before: always;
        }
        h3 {
            color: #2c3e50;
            margin-top: 20px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte Financiero</h1>
        <p>Sistema de Gestión de Cajas</p>
    </div>
    
    <div class="info">
        <strong>Fecha de generación:</strong> {{ $fecha_generacion }}<br>
        <strong>Período:</strong> {{ $fecha_inicio }} al {{ $fecha_fin }}<br>
        <strong>Sucursal:</strong> {{ $sucursal_nombre }}
    </div>
    
    <div class="resumen">
        <div class="card ingresos">
            <h3>Total Ingresos</h3>
            <div class="monto text-success">Bs {{ number_format($total_ingresos, 2) }}</div>
        </div>
        <div class="card egresos">
            <h3>Total Egresos</h3>
            <div class="monto text-danger">Bs {{ number_format($total_egresos, 2) }}</div>
        </div>
        <div class="card balance">
            <h3>Balance</h3>
            <div class="monto text-info">Bs {{ number_format($balance, 2) }}</div>
        </div>
    </div>
    
    <div class="metodos-pago">
        <div class="metodo-item">
            <div class="label">Efectivo</div>
            <div class="monto text-success">Bs {{ number_format($total_ingresos_efectivo, 2) }}</div>
        </div>
        <div class="metodo-item">
            <div class="label">QR</div>
            <div class="monto text-success">Bs {{ number_format($total_ingresos_qr, 2) }}</div>
        </div>
        <div class="metodo-item">
            <div class="label">Tarjeta</div>
            <div class="monto text-success">Bs {{ number_format($total_ingresos_tarjeta, 2) }}</div>
        </div>
    </div>
    
    @if(count($resumen_por_caja) > 0)
    <h3>Resumen por Caja</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Caja</th>
                <th>Sucursal</th>
                <th>Ingresos</th>
                <th>Egresos</th>
                <th>Balance</th>
                <th>Saldo Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resumen_por_caja as $caja)
            <tr>
                <td>{{ $caja['nombre'] }}</td>
                <td>{{ $caja['sucursal'] }}</td>
                <td class="text-success">Bs {{ number_format($caja['ingresos'], 2) }}</td>
                <td class="text-danger">Bs {{ number_format($caja['egresos'], 2) }}</td>
                <td class="text-info">Bs {{ number_format($caja['balance'], 2) }}</td>
                <td>Bs {{ number_format($caja['saldo_total'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    @if(count($ingresos) > 0)
    <h3>Detalle de Ingresos</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Caja</th>
                <th>Categoría</th>
                <th>Método Pago</th>
                <th>Descripción</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ingresos as $ingreso)
            <tr>
                <td>{{ Carbon\Carbon::parse($ingreso->fecha)->format('d/m/Y') }}</td>
                <td>{{ $ingreso->caja->nombre ?? 'N/A' }}</td>
                <td>{{ $ingreso->categoria }}</td>
                <td>{{ ucfirst($ingreso->metodo_pago) }}</td>
                <td>{{ $ingreso->descripcion }}</td>
                <td class="text-success">Bs {{ number_format($ingreso->monto, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    @if(count($egresos) > 0)
    <h3>Detalle de Egresos</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Caja</th>
                <th>Categoría</th>
                <th>Descripción</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($egresos as $egreso)
            <tr>
                <td>{{ Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y') }}</td>
                <td>{{ $egreso->caja->nombre ?? 'N/A' }}</td>
                <td>{{ $egreso->categoria }}</td>
                <td>{{ $egreso->descripcion }}</td>
                <td class="text-danger">Bs {{ number_format($egreso->monto, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema.</p>
        <p>© {{ date('Y') }} - Todos los derechos reservados</p>
    </div>
</body>
</html>