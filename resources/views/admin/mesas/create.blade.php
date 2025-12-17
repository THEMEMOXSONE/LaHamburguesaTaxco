<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Nueva Mesa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.mesas.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Mesa</label>
                        <input type="text" name="nombre" class="w-full border rounded py-2 px-3 focus:ring-2 focus:ring-green-500 outline-none" placeholder="Ej. Terraza 1, Barra 3" required>
                        @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Zona</label>
                        <select name="zona" class="w-full border rounded py-2 px-3 focus:ring-2 focus:ring-green-500 outline-none">
                            <option value="Terraza">Terraza</option>
                            <option value="Interior">Interior</option>
                            <option value="Planta Baja">Planta Baja</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Esto agrupará la mesa en el mapa del mesero.</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.mesas.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-bold">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-bold shadow">Guardar Mesa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
