<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Orden;
use App\Models\Producto;
use App\Models\DetalleOrden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pago;
use Carbon\Carbon;

class OrdenController extends Controller
{
    /**
     * Busca una orden abierta para la mesa o crea una nueva.
     */
    public function gestionarOrdenPorMesa(Mesa $mesa)
    {
        // 1) Buscar una orden que pertenezca a esta mesa y que no esté pagada
        $ordenActiva = $mesa->ordenes()
                            ->where('estado', '!=', 'Pagada')
                            ->first();

        // 2) Si existe, redirigir a esa orden
        if ($ordenActiva) {
            return redirect()->route('orden.mostrar', $ordenActiva);
        }

        // 3) Si no existe, crear nueva orden asociada a la mesa
        $nuevaOrden = $mesa->ordenes()->create([
            'usuario_id' => Auth::id(),
            'estado' => 'Activa',
            'total' => 0,
            'FECHA' => now()->toDateString(),
        ]);

        // 4) Marcar la mesa como ocupada
        $mesa->estado = 'ocupada';
        $mesa->save();

        // 5) Redirigir a la vista de la nueva orden
        return redirect()->route('orden.mostrar', $nuevaOrden);
    }

    /**
     * Muestra la orden específica (marcador de posición por ahora).
     */
    public function mostrarOrden(Orden $orden)
    {
        // Cargamos la relación de mesa para usar en la vista
        $orden->load('mesa');

        // Intentamos cargar categorías si existe el modelo Categoria, si no cargamos todos los productos
        $categorias = null;

        // Primero determinamos los IDs que son variantes (para evitar mostrar duplicados)
        $variantIds = \App\Models\Producto::pluck('id_variante_papas')->filter()->values()->all();

        if (class_exists(\App\Models\Categoria::class)) {
            $categorias = \App\Models\Categoria::whereHas('productos')
                ->with(['productos' => function($query) use ($variantIds) {
                    if (!empty($variantIds)) {
                        $query->whereNotIn('id_prod', $variantIds);
                    }
                    $query->orderBy('nombre');
                }])->orderBy('orden')->get();
        } else {
            // Si no hay tabla de categorias, creamos una categoría "Todos" con todos los productos
            $productosQuery = \App\Models\Producto::orderBy('nombre');
            if (!empty($variantIds)) {
                $productosQuery->whereNotIn('id_prod', $variantIds);
            }
            $productos = $productosQuery->get();
            $categorias = collect([ (object) [
                'id_categoria' => 0,
                'nombre' => 'Todos',
                'productos' => $productos
            ]]);
        }

        // Cargamos el carrito (detalles) con su producto
        $carrito = $orden->detalles()->with('producto')->get();

        return view('orden', [
            'orden' => $orden,
            'categorias' => $categorias,
            'carrito' => $carrito,
        ]);
    }

    /**
     * Agrega múltiples items a la orden (payload: items[])
     */
    public function agregarItems(Request $request, Orden $orden)
    {
        $datos = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id_prod',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.notas' => 'nullable|string'
        ]);

        $nuevoTotal = $orden->total ?? 0;

        foreach ($datos['items'] as $item) {
            $producto = Producto::find($item['producto_id']);
            if (! $producto) {
                continue;
            }

            $detalle = $orden->detalles()->create([
                'id_prod' => $producto->id_prod,
                'cantidad' => $item['cantidad'],
                'precio' => $producto->precio,
                'notas' => $item['notas'] ?? null,
            ]);

            $nuevoTotal += ($producto->precio * $item['cantidad']);
        }

        $orden->total = $nuevoTotal;
        $orden->save();

        return response()->json([
            'mensaje' => 'Items agregados',
            'nuevo_total' => $orden->total,
        ]);
    }

    public function actualizarCantidad(Request $request)
    {
        $request->validate([
            // Aquí le decimos: busca en la tabla 'detalle_orden', columna 'id_detalle'
            'detalle_id' => 'required|exists:detalle_orden,id_detalle', 
            'accion' => 'required|in:incrementar,decrementar'
        ]);

        // Buscamos usando el ID que nos llega
        $detalle = DetalleOrden::find($request->detalle_id);
        
        if($request->accion == 'incrementar') {
            $detalle->cantidad += 1;
        } else {
            if($detalle->cantidad > 1) {
                $detalle->cantidad -= 1;
            } else {
                return response()->json(['success' => false, 'message' => 'Mínimo 1']);
            }
        }
        
        $detalle->save();

        // Recalcular total orden
        $orden = $detalle->orden;
        $nuevoTotal = $orden->detalles->sum(function($item) {
            return $item->cantidad * $item->precio;
        });
        $orden->total = $nuevoTotal;
        $orden->save();

        return response()->json(['success' => true]);
    }

    public function eliminarDetalle(Request $request)
    {
        $request->validate([
            'detalle_id' => 'required|exists:detalle_orden,id_detalle'
        ]);

        $detalle = DetalleOrden::find($request->detalle_id);
        $orden = $detalle->orden;

        $detalle->delete();

        // Recalcular
        $orden->load('detalles');
        $nuevoTotal = $orden->detalles->sum(function($item) {
            return $item->cantidad * $item->precio;
        });
        $orden->total = $nuevoTotal;
        $orden->save();

        return response()->json(['success' => true]);
    }

    /**
     * Cierra la orden: la marca como pagada y libera la mesa.
     */
    public function cerrarOrden(Request $request, Orden $orden)
    {
        // Marcar orden como pagada
        $orden->estado = 'Pagada';
        $orden->save();

        // Liberar la mesa asociada si existe
        if ($orden->mesa) {
            $mesa = $orden->mesa;
            $mesa->estado = 'libre';
            $mesa->save();
        }

        return redirect()->route('mesas')->with('status', 'Orden cerrada correctamente');
    }

    /**
     * Procesa un pago (AJAX) y crea el registro en `pagos`.
     */
    public function pagarCuenta(Request $request)
    {
        $data = $request->validate([
            'orden_id' => 'required|exists:ordenes,id_orden',
            'metodo' => 'required|string',
            'monto_efectivo' => 'nullable|numeric|min:0',
            'monto_tarjeta' => 'nullable|numeric|min:0',
        ]);

        $orden = Orden::find($data['orden_id']);
        if (! $orden) {
            return response()->json(['success' => false, 'message' => 'Orden no encontrada'], 404);
        }

        // Preparar montos según método (lógica robusta que sugirió el usuario)
        $montoEfectivo = 0;
        $montoTarjeta = 0;

        if ($data['metodo'] === 'efectivo') {
            $montoEfectivo = floatval($orden->total);
            $montoTarjeta = 0;
        } elseif ($data['metodo'] === 'tarjeta') {
            $montoEfectivo = 0;
            $montoTarjeta = floatval($orden->total);
        } else {
            // Mixto: tomar los montos enviados, permitir nulls
            $montoEfectivo = isset($data['monto_efectivo']) ? floatval($data['monto_efectivo']) : 0;
            $montoTarjeta = isset($data['monto_tarjeta']) ? floatval($data['monto_tarjeta']) : 0;
        }

        // Validación de suma (tolerancia pequeña para redondeos)
        $totalCalculado = round($montoEfectivo + $montoTarjeta, 2);
        $ordenTotal = round(floatval($orden->total), 2);
        if (abs($totalCalculado - $ordenTotal) > 0.5) {
            return response()->json(['success' => false, 'message' => 'Los montos no coinciden con el total.'], 422);
        }

        // Crear registro de pago
        $pago = new Pago();
        $pago->id_orden = $orden->id_orden;
        $pago->montoEfectivo = $montoEfectivo;
        $pago->montoTarjeta = $montoTarjeta;
        $pago->metodoPago = $data['metodo'];
        $pago->fecha = Carbon::now()->toDateString();
        $pago->save();

        // Actualizar orden y liberar mesa
        $orden->estado = 'Pagada';
        $orden->save();

        if ($orden->mesa) {
            $orden->mesa->estado = 'disponible';
            $orden->mesa->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Imprime el ticket completo para el cliente
     */
    public function imprimirTicket(Orden $orden)
    {
        $orden->load('detalles.producto', 'pago');
        return view('ticket', compact('orden'));
    }

    /**
     * Imprime la comanda para cocina (solo productos y notas)
     */
    public function imprimirComanda(Orden $orden)
    {
        $orden->load('detalles.producto', 'mesa', 'usuario');
        return view('comanda', compact('orden'));
    }
}
