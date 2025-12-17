<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Mesas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-700">Listado de Mesas</h3>
                    <a href="{{ route('admin.mesas.create') }}" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600 transition">+ Nueva Mesa</a>
                </div>

                <table class="min-w-full border rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nombre</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Zona</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Estado Actual</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mesas as $mesa)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-5 py-3 font-bold text-gray-800">{{ $mesa->nombre }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $mesa->zona ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $mesa->estado == 'ocupada' ? 'bg-red-200 text-red-800' : 'bg-green-200 text-green-800' }}">{{ strtoupper($mesa->estado) }}</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <a href="{{ route('admin.mesas.edit', $mesa->id_mesa) }}" class="text-blue-600 font-bold mr-3 hover:underline">Editar</a>
                                <form action="{{ route('admin.mesas.destroy', $mesa->id_mesa) }}" method="POST" class="inline" onsubmit="return confirm('¿Borrar mesa?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 font-bold hover:underline">Borrar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
