{{-- resources/views/mesas.blade.php --}}

@push('styles')
    @vite(['resources/css/mesas.css'])
@endpush

@push('scripts')
    @vite(['resources/js/mesas.js'])
@endpush

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- 1. SELECTOR DE ZONAS DINÁMICO --}}
                    <div class="mesa-selector-container">
                        <label for="zona-selector" class="font-bold mr-2">Zona:</label>
                        <select id="zona-selector" class="zona-selector border rounded px-3 py-1">
                            @foreach($mesasPorZona as $zona => $mesas)
                                {{-- Usamos Str::slug para crear un ID seguro (ej. "Planta Baja" -> "planta-baja") --}}
                                <option value="{{ Str::slug($zona) }}">{{ $zona }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. CONTENEDORES DE MESAS DINÁMICOS --}}
                    @foreach($mesasPorZona as $zona => $mesas)
                        {{-- 
                            El ID se genera igual que en el select (mapa-mesas-planta-baja).
                            Solo el primero ($loop->first) se muestra activo, los demás hidden.
                        --}}
                        <div id="mapa-mesas-{{ Str::slug($zona) }}" class="mapa-mesas {{ $loop->first ? 'active' : 'hidden' }}">
                            <h3 class="text-xl font-bold mb-4 border-b pb-2">{{ $zona }}</h3>
                            
                            <div class="mesa-grid">
                                @foreach($mesas as $mesa)
                                    {{-- Lógica de color según estado --}}
                                    <div class="mesa {{ $mesa->estado === 'ocupada' ? 'ocupada' : 'libre' }}" 
                                         data-mesa-id="{{ $mesa->id_mesa }}">
                                        {{ $mesa->nombre }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- MENSAJE POR SI NO HAY MESAS --}}
                    @if($mesasPorZona->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            <p>No hay mesas registradas en el sistema.</p>
                            <a href="{{ route('admin.mesas.create') }}" class="text-blue-500 hover:underline">Crear mesas aquí</a>
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>