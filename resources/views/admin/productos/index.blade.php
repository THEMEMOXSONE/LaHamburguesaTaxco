<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Menú
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <form method="GET" action="{{ route('admin.productos.index') }}" class="flex gap-2">
                        <input type="text" name="search" placeholder="Buscar..." class="border rounded px-4 py-2" value="{{ request('search') }}">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded font-bold">Buscar</button>
                    </form>
                    <a href="{{ route('admin.productos.create') }}" class="bg-green-500 text-white font-bold py-2 px-4 rounded">+ Nuevo</a>
                </div>

                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-5 py-3 text-left">Nombre</th>
                            <th class="px-5 py-3 text-left">Descripción</th>
                            <th class="px-5 py-3 text-left">Precio</th>
                            <th class="px-5 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $prod)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-5 py-3 font-bold">{{ $prod->nombre }}</td>
                            <td class="px-5 py-3 text-gray-500 text-sm">{{ Str::limit($prod->descripcion, 30) }}</td>
                            <td class="px-5 py-3 text-green-600 font-bold">${{ number_format($prod->precio, 2) }}</td>
                            <td class="px-5 py-3 text-center">
                                {{-- IMPORTANTE: Usamos $prod->id_prod --}}
                                <a href="{{ route('admin.productos.edit', $prod->id_prod) }}" class="text-blue-600 font-bold mr-3">Editar</a>
                                
                                <form action="{{ route('admin.productos.destroy', $prod->id_prod) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('¿Borrar {{ $prod->nombre }}?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 font-bold">Borrar</button>
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