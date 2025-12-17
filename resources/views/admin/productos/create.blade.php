<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Agregar Nuevo Platillo</h2>
    </x-slot>

    <div class="py-12">
        {{-- AQUÍ CAMBIAMOS EL TAMAÑO TAMBIÉN: De max-w-3xl a max-w-5xl --}}
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                
                {{-- ENCTYPE es obligatorio para subir archivos --}}
                <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- 1. NOMBRE Y PRECIO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Producto</label>
                            <input type="text" name="nombre" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required autofocus placeholder="Ej. Hamburguesa Hawaiana">
                            @error('nombre') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Precio ($)</label>
                            <input type="number" step="0.01" name="precio" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="0.00">
                        </div>
                    </div>

                    {{-- 2. CATEGORÍA Y VARIANTE PAPAS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 bg-gray-50 p-4 rounded border">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Categoría</label>
                            {{-- ID 'select_categoria' para el JS --}}
                            <select name="categoria_id" id="select_categoria" class="w-full border rounded py-2 px-3 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="2">Hamburguesa</option>
                                <option value="1">Entrada / Extra</option>
                                <option value="3">Desayunos</option>
                                <option value="4">Algo más</option>
                                <option value="5">Café y postres</option>
                                <option value="6">Coctelería</option>
                                <option value="7">Bebidas</option>
                                <option value="8">Barra</option>
                            </select>
                        </div>

                        {{-- Este div se oculta/muestra con JS --}}
                        <div id="div_variante_papas">
                            <label class="block text-gray-700 text-sm font-bold mb-2 text-green-700">
                                ID Variante "Con Papas"
                                <span class="text-xs font-normal text-gray-500">(Opcional)</span>
                            </label>
                            <input type="number" name="id_variante_papas" class="w-full border rounded py-2 px-3 border-green-200 focus:ring-green-500" placeholder="Ej. 18">
                            <p class="text-xs text-gray-500 mt-1">Escribe el ID del producto que corresponde a la versión con papas de este platillo.</p>
                        </div>
                    </div>

                    {{-- 3. IMAGEN --}}
                    <div class="mb-6 border-2 border-dashed border-gray-300 p-4 rounded text-center hover:bg-gray-50 transition">
                        <label class="block text-gray-700 text-sm font-bold mb-3">Imagen del Producto</label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Paso 1: Selecciona el archivo</label>
                                <input type="file" name="imagen_archivo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Paso 2: Nombre para guardar (Sin espacios)</label>
                                <div class="flex items-center">
                                    <input type="text" name="nombre_imagen" class="w-full border rounded py-1 px-3 text-sm" placeholder="ej: hamburguesa-hawaiana" required>
                                    <span class="ml-2 text-gray-400 text-sm">.jpg/png</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Esto será la URL de la imagen en el sistema.</p>
                            </div>
                        </div>
                        @error('nombre_imagen') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        @error('imagen_archivo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- 4. DESCRIPCIÓN --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                        <textarea name="descripcion" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2" placeholder="Ingredientes o detalles..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.productos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-bold transition">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-bold shadow-lg transition">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT PARA MOSTRAR/OCULTAR INPUT DE PAPAS --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('select_categoria');
            const divPapas = document.getElementById('div_variante_papas');

            function togglePapas() {
                // Si es Hamburguesa (ID 2), mostramos. Si no, ocultamos.
                if (select.value == '2') {
                    divPapas.style.display = 'block';
                    // Habilitamos el input para que se envíe
                    const input = divPapas.querySelector('input');
                    if(input) input.disabled = false;
                } else {
                    divPapas.style.display = 'none';
                    // Deshabilitamos y limpiamos para no enviar datos basura
                    const input = divPapas.querySelector('input');
                    if(input) {
                        input.disabled = true; 
                        input.value = ''; 
                    }
                }
            }

            // Ejecutar al inicio y al cambiar
            select.addEventListener('change', togglePapas);
            togglePapas(); // Check inicial por si recarga la página con datos viejos
        });
    </script>
    @endpush
</x-app-layout>