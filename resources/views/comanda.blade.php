<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comanda - Cocina</title>
    <style>
        body { font-family: monospace; width: 320px; font-size: 14px; font-weight: bold; }
        .center { text-align: center; }
        .divider { border-top: 2px solid #000; margin: 10px 0; }
        .nota { font-size: 12px; font-weight: normal; }
    </style>
</head>
<body onload="window.print()">
    <div class="center">
        <p>--- ORDEN DE COCINA ---</p>
        <p>MESA: {{ $orden->mesa->nombre }}</p>
        <p>Mesero: {{ $orden->usuario->name ?? 'N/A' }}</p>
        <p>Hora: {{ date('H:i') }}</p>
    </div>

    <div class="divider"></div>

    @foreach($orden->detalles as $detalle)
        <div style="margin-bottom: 8px;">
            <div>{{ $detalle->cantidad }} x {{ $detalle->producto->nombre }}</div>
            @if($detalle->notas)
                <div class="nota">⚠️ Nota: {{ $detalle->notas }}</div>
            @endif
        </div>
    @endforeach

    <div class="divider"></div>
</body>
</html>