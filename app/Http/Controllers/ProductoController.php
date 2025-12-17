<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // IMPORTANTE: Para poder borrar imágenes viejas

class ProductoController extends Controller
{
    // 1. CONSULTAS
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->filled('search')) {
            $query->where('nombre', 'LIKE', "%{$request->search}%");
        }

        $productos = $query->orderBy('nombre')->get();
        return view('admin.productos.index', compact('productos'));
    }

    // VISTA CREAR
    public function create()
    {
        return view('admin.productos.create');
    }

    // 2. ALTAS (CON SUBIDA DE IMAGEN)
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:productos,nombre',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|integer',
            'descripcion' => 'nullable|string',
            'id_variante_papas' => 'nullable|integer',
            // Validamos que suban un archivo de imagen real
            'imagen_archivo' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', 
            // Validamos el nombre personalizado
            'nombre_imagen' => 'required|string|max:50|alpha_dash', 
        ], [
            'nombre.unique' => 'Error: Ya existe un producto con este nombre.',
            'nombre_imagen.alpha_dash' => 'El nombre de imagen no puede llevar espacios (usa guiones -).',
        ]);

        // --- PROCESAMIENTO DE IMAGEN ---
        $nombreFinalImagen = 'default.png';

        if ($request->hasFile('imagen_archivo')) {
            $archivo = $request->file('imagen_archivo');
            $extension = $archivo->getClientOriginalExtension();
            // Creamos el nombre: "hamburguesa-hawaiana.jpg"
            $nombreFinalImagen = $request->nombre_imagen . '.' . $extension;
            
            // Movemos el archivo a la carpeta public/images
            $archivo->move(public_path('images'), $nombreFinalImagen);
        }

        // Guardamos en la BD
        Producto::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'id_variante_papas' => $request->id_variante_papas,
            'imagen_url' => $nombreFinalImagen, // Guardamos solo el nombre del archivo
        ]);

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado exitosamente.');
    }

    // VISTA EDITAR
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('admin.productos.edit', compact('producto'));
    }

    // 3. MODIFICACIONES (CON REEMPLAZO DE IMAGEN)
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|integer',
            'descripcion' => 'nullable|string',
            'id_variante_papas' => 'nullable|integer',
            // En editar la imagen es opcional (nullable)
            'imagen_archivo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'nombre_imagen' => 'nullable|string|max:50|alpha_dash',
        ]);

        // Actualizamos datos básicos
        $producto->precio = $request->precio;
        $producto->categoria_id = $request->categoria_id;
        $producto->descripcion = $request->descripcion;
        $producto->id_variante_papas = $request->id_variante_papas;

        // --- LÓGICA DE REEMPLAZO DE IMAGEN ---
        // Solo entramos aquí si el usuario seleccionó un archivo nuevo
        if ($request->hasFile('imagen_archivo')) {
            
            // 1. Borrar la imagen viejita (Limpieza)
            // Solo borramos si existe y NO es la imagen por defecto
            if ($producto->imagen_url && $producto->imagen_url != 'default.png') {
                $rutaVieja = public_path('images/' . $producto->imagen_url);
                if (File::exists($rutaVieja)) {
                    File::delete($rutaVieja);
                }
            }

            // 2. Subir la nueva imagen
            $archivo = $request->file('imagen_archivo');
            $extension = $archivo->getClientOriginalExtension();
            
            // Si puso un nombre personalizado lo usamos, si no, usamos el nombre del producto + tiempo
            $nombreBase = $request->nombre_imagen ?? 'prod_' . time();
            $nombreFinalImagen = $nombreBase . '.' . $extension;
            
            $archivo->move(public_path('images'), $nombreFinalImagen);
            
            // 3. Actualizar el nombre en la BD
            $producto->imagen_url = $nombreFinalImagen;
        }

        $producto->save();

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    // 4. BAJAS
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        
        try {
            // Opcional: Borrar imagen del servidor al eliminar el producto
            if ($producto->imagen_url && $producto->imagen_url != 'default.png') {
                $ruta = public_path('images/' . $producto->imagen_url);
                if (File::exists($ruta)) {
                    File::delete($ruta);
                }
            }

            $producto->delete();
            return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado.');
        } catch (\Exception $e) {
            return redirect()->route('admin.productos.index')->with('error', 'No se puede eliminar porque está en uso.');
        }
    }
}