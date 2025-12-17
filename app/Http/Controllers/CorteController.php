<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Corte;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CorteController extends Controller
{
    // VISTA PRINCIPAL: Panel de Cortes
    public function index()
    {
        $cortes = Corte::orderBy('fecha', 'desc')->paginate(10);
        return view('admin.cortes.index', compact('cortes'));
    }

    // GENERAR REPORTE: diario o por plazo
    public function generarReporte(Request $request)
    {
        $tipo = $request->input('tipo');

        if ($tipo == 'diario') {
            $fecha = $request->input('fecha', date('Y-m-d'));
            $pagos = Pago::whereDate('fecha', $fecha)->get();

            $totalEfectivo = $pagos->sum('montoEfectivo');
            $totalTarjeta = $pagos->sum('montoTarjeta');
            $total = $totalEfectivo + $totalTarjeta;

            $datos = [
                'titulo' => "Reporte del Día " . Carbon::parse($fecha)->format('d/m/Y'),
                'rango' => $fecha,
                'efectivo' => $totalEfectivo,
                'tarjeta' => $totalTarjeta,
                'total' => $total,
                'detalles' => $pagos
            ];

            return view('admin.cortes.reporte', compact('datos'));
        }

        if ($tipo == 'plazo') {
            $inicio = $request->input('fecha_inicio');
            $fin = $request->input('fecha_fin');

            $pagos = Pago::whereBetween('fecha', [$inicio, $fin])->get();

            $totalEfectivo = $pagos->sum('montoEfectivo');
            $totalTarjeta = $pagos->sum('montoTarjeta');
            $total = $totalEfectivo + $totalTarjeta;

            $datos = [
                'titulo' => "Reporte del " . Carbon::parse($inicio)->format('d/m/Y') . " al " . Carbon::parse($fin)->format('d/m/Y'),
                'rango' => "$inicio a $fin",
                'efectivo' => $totalEfectivo,
                'tarjeta' => $totalTarjeta,
                'total' => $total,
                'detalles' => $pagos
            ];

            return view('admin.cortes.reporte', compact('datos'));
        }
    }

    // GUARDAR CORTE (opcional)
    public function guardarCorte(Request $request)
    {
        Corte::create([
            'usuario_id' => Auth::id(),
            'fecha' => now(),
            'monto_inicial' => $request->input('monto_inicial', 0),
            'monto_final' => $request->input('monto_recaudado', 0),
            'monto_calculado' => $request->input('monto_sistema', 0),
        ]);

        return redirect()->route('admin.cortes.index')->with('success', 'Corte guardado correctamente');
    }
}
