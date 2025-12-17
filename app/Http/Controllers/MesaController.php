<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    /**
     * Muestra el mapa de mesas (dinámico).
     */
    public function index()
    {
        // 1. Obtenemos todas las mesas
        $mesas = Mesa::orderBy('id_mesa')->get();

        // 2. Las separamos por zona según los id_mesa existentes
        $mesasPorZona = $mesas->groupBy('zona');

        // 3. Retornamos la vista con las colecciones
        return view('mesas', compact('mesasPorZona'));
    }
}
