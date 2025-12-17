<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cortes de Caja y Reportes</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- TARJETA 1: REPORTE DIARIO --}}
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-blue-600">📅 Reporte Diario</h3>
                    <form action="{{ route('admin.cortes.reporte') }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="tipo" value="diario">
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Seleccionar Fecha</label>
                            <input type="date" name="fecha" value="{{ date('Y-m-d') }}" class="w-full border rounded px-3 py-2">
                        </div>

                        <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 rounded hover:bg-blue-600">
                            Generar PDF
                        </button>
                    </form>
                </div>

                {{-- TARJETA 2: REPORTE POR PLAZO --}}
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-green-600">📆 Reporte por Plazo</h3>
                    <form action="{{ route('admin.cortes.reporte') }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="tipo" value="plazo">
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Inicio</label>
                                <input type="date" name="fecha_inicio" value="{{ date('Y-m-01') }}" class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Fin</label>
                                <input type="date" name="fecha_fin" value="{{ date('Y-m-d') }}" class="w-full border rounded px-3 py-2">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-green-500 text-white font-bold py-2 rounded hover:bg-green-600">
                            Generar PDF
                        </button>
                    </form>
                </div>

            </div>

            {{-- HISTORIAL DE CORTES (OPCIONAL) --}}
            <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
                <h3 class="font-bold mb-4">Historial de Cortes Guardados</h3>
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Usuario</th>
                            <th class="px-4 py-2">Sistema</th>
                            <th class="px-4 py-2">Real (Caja)</th>
                            <th class="px-4 py-2">Diferencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cortes as $corte)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $corte->fecha }}</td>
                            <td class="px-4 py-2">{{ $corte->usuario->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">${{ number_format($corte->monto_calculado, 2) }}</td>
                            <td class="px-4 py-2">${{ number_format($corte->monto_final, 2) }}</td>
                            <td class="px-4 py-2 font-bold {{ ($corte->monto_final - $corte->monto_calculado) < 0 ? 'text-red-500' : 'text-green-500' }}">
                                ${{ number_format($corte->monto_final - $corte->monto_calculado, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $cortes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
