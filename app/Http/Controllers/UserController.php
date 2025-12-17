<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // CONSULTAS: Muestra la lista con BUSCADOR INTELIGENTE
    public function index(Request $request)
    {
        // Iniciamos la consulta base trayendo los roles
        $query = User::with('roles');

        // Si el usuario escribió algo en el buscador...
        if ($request->filled('search')) {
            $search = $request->input('search');
            
            // Filtramos por Nombre O por Email que contenga el texto
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Ejecutamos la consulta
        $users = $query->get();

        return view('admin.users.index', compact('users'));
    }

    // VISTA ALTA
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // GUARDAR (ALTA)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,id_rol',
        ], [
            'email.unique' => 'Error: Este correo electrónico ya está registrado.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->role);

        return redirect()->route('admin.users.index')->with('success', 'Usuario registrado correctamente.');
    }

    // VISTA EDICIÓN
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // ACTUALIZAR (MODIFICACIÓN)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|exists:roles,id_rol',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $request->name;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // No tocamos el email (campo llave)
        $user->save();

        // Actualizamos roles
        $user->roles()->sync([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // BAJAS (ELIMINAR)
    public function destroy($id)
    {
        // 1. Protección: No te puedes borrar a ti mismo
        if (auth()->user()->id == $id) {
            return redirect()->route('admin.users.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user = User::findOrFail($id);

        // 2. Despegamos los roles antes de borrar
        $user->roles()->detach(); 

        // 3. Borramos al usuario
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado del sistema.');
    }
}