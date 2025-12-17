<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket - La Hamburguesa</title>
    <style>
        body { font-family: monospace; width: 320px; font-size: 12px; }
        .center { text-align: center; }
        .flex { display:flex; justify-content:space-between; }
        .divider { border-top: 1px dashed #000; margin: 6px 0; }
        h3 { margin: 6px 0; }
    </style>
</head>
<body onload="window.print()">
    <div class="center">
        <h3>LA HAMBURGUESA S.A. DE C.V.</h3>
        <div>La Plazuela de San Juan 5 Centro CP 40200</div>
        <div>Taxco de Alarcón, Guerrero</div>
        <div>RFC: 12335463DS</div>
        <p>Folio: #{{ $orden->id_orden }}</p>
        <p>Fecha: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="divider"></div>

    @foreach($orden->detalles as $detalle)
    <div class="flex">
        <div>{{ str_pad($detalle->cantidad, 2, '0', STR_PAD_LEFT) }} x {{ $detalle->producto->nombre }}</div>
        <div>${{ number_format($detalle->precio * $detalle->cantidad, 2) }}</div>
    </div>
    @if($detalle->notas)
        <div style="font-size:11px; color:#333;">Nota: {{ $detalle->notas }}</div>
    @endif
    @endforeach

    <div class="divider"></div>

    <div class="flex" style="font-weight: bold; font-size: 14px;">
        <div>TOTAL:</div>
        <div>${{ number_format($orden->total, 2) }}</div>
    </div>

    <div style="margin-top:10px;">
        <p class="center">--- DETALLE DE PAGO ---</p>
        @if($orden->pago)
            <div class="flex"><span>Método:</span><span>{{ strtoupper($orden->pago->metodoPago) }}</span></div>
            @if($orden->pago->montoEfectivo > 0)
                <div class="flex"><span>Efectivo:</span><span>${{ number_format($orden->pago->montoEfectivo, 2) }}</span></div>
            @endif
            @if($orden->pago->montoTarjeta > 0)
                <div class="flex"><span>Tarjeta:</span><span>${{ number_format($orden->pago->montoTarjeta, 2) }}</span></div>
            @endif
        @endif
    </div>

    <div class="center" style="margin-top: 16px;">
        <div>Recuerda visitar nuestra página: www.LaHamburguesaTaxco.com</div>
        <div>GRACIAS POR LA VISITA! ^.^</div>
    </div>
</body>
</html>