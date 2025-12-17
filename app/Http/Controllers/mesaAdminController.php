<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaAdminController extends Controller
{
    // 1. LISTAR MESAS
    public function index()
    {
        $mesas = Mesa::orderBy('nombre')->get();
        return view('admin.mesas.index', compact('mesas'));
    }

    // 2. VISTA CREAR
    public function create()
    {
        return view('admin.mesas.create');
    }

    // 3. GUARDAR (STORE)
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:mesas,nombre',
            'zona' => 'required|string',
        ], [
            'nombre.unique' => 'Ya existe una mesa con ese nombre.'
        ]);

        Mesa::create([
            'nombre' => $request->nombre,
            'zona' => $request->zona,
            'estado' => 'disponible'
        ]);

        return redirect()->route('admin.mesas.index')->with('success', 'Mesa creada correctamente.');
    }

    // 4. VISTA EDITAR
    public function edit($id)
    {
        $mesa = Mesa::findOrFail($id);
        return view('admin.mesas.edit', compact('mesa'));
    }

    // 5. ACTUALIZAR (UPDATE)
    public function update(Request $request, $id)
    {
        $mesa = Mesa::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:50|unique:mesas,nombre,'.$id.',id_mesa',
            'zona' => 'required|string',
        ]);

        $mesa->update([
            'nombre' => $request->nombre,
            'zona' => $request->zona,
        ]);

        return redirect()->route('admin.mesas.index')->with('success', 'Mesa actualizada.');
    }

    // 6. ELIMINAR (DESTROY)
    public function destroy($id)
    {
        $mesa = Mesa::findOrFail($id);
        $mesa->delete();
        return redirect()->route('admin.mesas.index')->with('success', 'Mesa eliminada.');
    }
}

