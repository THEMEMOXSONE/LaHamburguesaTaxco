<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menú Administrativo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- CAMBIO: Grid ajustable para acomodar hasta 4 tarjetas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- OPCIÓN 1: IR A OPERACIÓN (MESAS) - AMARILLO --}}
                <a href="{{ route('mesas') }}" class="group block">
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border-2 border-transparent hover:border-yellow-400 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 text-yellow-600 group-hover:bg-yellow-400 group-hover:text-white transition-colors">
                                {{-- Icono Mesas --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Operación / Mesas</h3>
                            <p class="text-sm text-gray-500">Acceder al mapa de mesas, tomar órdenes y cobrar cuentas.</p>
                        </div>
                    </div>
                </a>

                {{-- OPCIÓN 2: ADMINISTRACIÓN DE USUARIOS (ROJO) --}}
                {{-- Nota: Apuntamos a admin.users.index directamente --}}
                <a href="{{ route('admin.users.index') }}" class="group block">
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border-2 border-transparent hover:border-red-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600 group-hover:bg-red-500 group-hover:text-white transition-colors">
                                {{-- Icono Admin --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Gestión de Usuarios</h3>
                            <p class="text-sm text-gray-500">Altas, Bajas y Modificaciones de personal administrativo.</p>
                        </div>
                    </div>
                </a>

                {{-- OPCIÓN 3: GESTIÓN DE PRODUCTOS (AZUL) - NUEVA --}}
                <a href="{{ route('admin.productos.index') }}" class="group block">
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border-2 border-transparent hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-600 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                                {{-- Icono Productos --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Menú / Productos</h3>
                            <p class="text-sm text-gray-500">Altas, Bajas y Precios de platillos e ingredientes.</p>
                        </div>
                    </div>
                </a>

                {{-- OPCIÓN 4: GESTIÓN DE MESAS (VERDE) - NUEVA --}}
                <a href="{{ route('admin.mesas.index') }}" class="group block">
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border-2 border-transparent hover:border-green-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600 group-hover:bg-green-500 group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Configurar Mesas</h3>
                            <p class="text-sm text-gray-500">Agregar o quitar mesas y cambiar zonas.</p>
                        </div>
                    </div>
                </a>

                {{-- OPCIÓN 5: CORTES Y REPORTES (MORADO) --}}
                <a href="{{ route('admin.cortes.index') }}" class="group block">
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border-2 border-transparent hover:border-purple-600 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                {{-- Icono Reportes --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17a2 2 0 100-4 2 2 0 000 4zm6 0a2 2 0 100-4 2 2 0 000 4zM7 17a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Cortes y Reportes</h3>
                            <p class="text-sm text-gray-500">Generar reportes diarios o por rango y gestionar cortes de caja.</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>