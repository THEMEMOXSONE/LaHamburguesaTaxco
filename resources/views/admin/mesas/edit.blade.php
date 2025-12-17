<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Mesa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.mesas.update', $mesa->id_mesa) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
                        <input type="text" name="nombre" value="{{ $mesa->nombre }}" class="w-full border rounded py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Zona</label>
                        <select name="zona" class="w-full border rounded py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="Terraza" {{ $mesa->zona == 'Terraza' ? 'selected' : '' }}>Terraza</option>
                            <option value="Interior" {{ $mesa->zona == 'Interior' ? 'selected' : '' }}>Interior</option>
                            <option value="Planta Baja" {{ $mesa->zona == 'Planta Baja' ? 'selected' : '' }}>Planta Baja</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.mesas.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-bold">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-bold shadow">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
