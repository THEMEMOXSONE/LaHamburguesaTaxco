<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modificar Producto</h2>
    </x-slot>

    <div class="py-12">
        {{-- AQUÍ CAMBIAMOS EL TAMAÑO: De max-w-3xl a max-w-5xl --}}
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                
                {{-- Usamos id_prod en la ruta y multipart para imágenes --}}
                <form action="{{ route('admin.productos.update', $producto->id_prod) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    
                    {{-- 1. NOMBRE (SOLO LECTURA) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre (No editable)</label>
                        <input type="text" value="{{ $producto->nombre }}" class="w-full border rounded py-2 px-3 bg-gray-200 text-gray-600 cursor-not-allowed focus:outline-none" readonly>
                        <p class="text-xs text-gray-500 mt-1">Para cambiar el nombre, es necesario eliminar y crear un nuevo producto.</p>
                    </div>

                    {{-- 2. PRECIO --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Precio ($)</label>
                        <input type="number" step="0.01" name="precio" value="{{ $producto->precio }}" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    {{-- 3. CATEGORÍA Y VARIANTE PAPAS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 bg-gray-50 p-4 rounded border">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Categoría</label>
                            <select name="categoria_id" id="select_categoria" class="w-full border rounded py-2 px-3 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500">
                                {{-- Lógica ternaria para seleccionar la opción guardada --}}
                                <option value="2" {{ $producto->categoria_id == 2 ? 'selected' : '' }}>Hamburguesa</option>
                                <option value="1" {{ $producto->categoria_id == 1 ? 'selected' : '' }}>Entrada / Extra</option>
                                <option value="3" {{ $producto->categoria_id == 3 ? 'selected' : '' }}>Desayunos</option>
                                <option value="4" {{ $producto->categoria_id == 4 ? 'selected' : '' }}>Algo más</option>
                                <option value="5" {{ $producto->categoria_id == 5 ? 'selected' : '' }}>Café y postres</option>
                                <option value="6" {{ $producto->categoria_id == 6 ? 'selected' : '' }}>Coctelería</option>
                                <option value="7" {{ $producto->categoria_id == 7 ? 'selected' : '' }}>Bebidas</option>
                                <option value="8" {{ $producto->categoria_id == 8 ? 'selected' : '' }}>Barra</option>
                            </select>
                        </div>

                        {{-- Este div se oculta/muestra con JS --}}
                        <div id="div_variante_papas">
                            <label class="block text-gray-700 text-sm font-bold mb-2 text-green-700">
                                ID Variante "Con Papas"
                            </label>
                            <input type="number" name="id_variante_papas" value="{{ $producto->id_variante_papas }}" class="w-full border rounded py-2 px-3 border-green-200 focus:ring-green-500" placeholder="Ej. 18">
                        </div>
                    </div>

                    {{-- 4. IMAGEN ACTUAL Y CAMBIO --}}
                    <div class="mb-6 border-t pt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-3">Gestión de Imagen</label>
                        
                        <div class="flex flex-col md:flex-row gap-6 items-start">
                            {{-- Preview Imagen Actual --}}
                            <div class="w-full md:w-1/3 text-center bg-gray-100 p-2 rounded">
                                <span class="block text-xs font-bold text-gray-500 mb-2">Imagen Actual</span>
                                <img src="{{ asset('images/' . $producto->imagen_url) }}" alt="Producto" class="w-full h-auto rounded border shadow-sm object-cover max-h-40 mx-auto" 
                                     onerror="this.src='https://via.placeholder.com/150?text=Sin+Imagen'">
                                <p class="text-xs text-gray-400 mt-1 break-all">{{ $producto->imagen_url }}</p>
                            </div>

                            {{-- Subir Nueva (Opcional) --}}
                            <div class="w-full md:w-2/3 border-l pl-0 md:pl-6 border-gray-200">
                                <div class="mb-3">
                                    <label class="block text-xs font-bold text-blue-600 mb-1">¿Cambiar Imagen? (Opcional)</label>
                                    <input type="file" name="imagen_archivo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Nombre para el nuevo archivo</label>
                                    <div class="flex items-center">
                                        <input type="text" name="nombre_imagen" class="w-full border rounded py-1 px-3 text-sm" placeholder="nuevo-nombre-archivo">
                                        <span class="ml-2 text-gray-400 text-sm">.jpg</span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Llenar solo si vas a subir una imagen nueva.</p>
                                </div>
                                @error('nombre_imagen') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                @error('imagen_archivo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 5. DESCRIPCIÓN --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                        <textarea name="descripcion" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3">{{ $producto->descripcion }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.productos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-bold transition">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 font-bold shadow transition">Actualizar Producto</button>
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
            const inputPapas = divPapas.querySelector('input');

            function togglePapas() {
                // Si es Hamburguesa (ID 2), mostramos el campo de variante
                if (select.value == '2') {
                    divPapas.style.display = 'block';
                    inputPapas.disabled = false;
                } else {
                    divPapas.style.display = 'none';
                    inputPapas.disabled = true;
                }
            }

            select.addEventListener('change', togglePapas);
            
            // Ejecutar al inicio para aplicar el estado correcto según lo que viene de la BD
            togglePapas();
        });
    </script>
    @endpush
</x-app-layout>