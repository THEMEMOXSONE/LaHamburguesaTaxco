<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $datos['titulo'] }}</title>
    <style>
        body { font-family: sans-serif; padding: 40px; color: #333; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .resumen-box { display: flex; justify-content: space-around; margin-bottom: 30px; background: #f9f9f9; padding: 20px; border-radius: 8px; }
        .stat { text-align: center; }
        .stat-label { font-size: 14px; color: #666; }
        .stat-value { font-size: 24px; font-weight: bold; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border-bottom: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f1f1f1; }
        .print-btn { display: block; margin: 20px auto; padding: 10px 20px; cursor: pointer; background: #333; color: #fff; border: none; border-radius: 5px; }
        
        @media print {
            .print-btn { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">🖨️ Imprimir / Guardar PDF</button>

    <div class="header">
        <div class="logo">LA HAMBURGUESA S.A. DE C.V.</div>
        <div>{{ $datos['titulo'] }}</div>
        <small>Generado el: {{ date('d/m/Y H:i') }}</small>
    </div>

    <div class="resumen-box">
        <div class="stat">
            <div class="stat-label">Total Efectivo</div>
            <div class="stat-value">${{ number_format($datos['efectivo'], 2) }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Total Tarjeta</div>
            <div class="stat-value">${{ number_format($datos['tarjeta'], 2) }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">VENTA TOTAL</div>
            <div class="stat-value" style="color: green;">${{ number_format($datos['total'], 2) }}</div>
        </div>
    </div>

    <h3>Detalle de Transacciones</h3>
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Folio Orden</th>
                <th>Método</th>
                <th>Efectivo</th>
                <th>Tarjeta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos['detalles'] as $pago)
            <tr>
                <td>{{ \Carbon\Carbon::parse($pago->created_at)->format('H:i') }}</td>
                <td>#{{ $pago->id_orden }}</td>
                <td>{{ ucfirst($pago->metodoPago) }}</td>
                <td>${{ number_format($pago->montoEfectivo, 2) }}</td>
                <td>${{ number_format($pago->montoTarjeta, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
