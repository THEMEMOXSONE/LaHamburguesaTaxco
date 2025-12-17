<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

            <header class="main-header">
                <div class="logo-container">
                    <a href="{{ route('mesas') }}"> 
                        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo La Hamburguesa Taxco" class="w-20 h-20 rounded-full">
                    </a>
                </div>
                <button class="menu-toggle" id="menuToggle">
                    ☰
                </button>
                <nav class="main-nav" id="mainNav">
                    <ul>
                        {{-- ==================================================== --}}
                        {{-- BOTÓN EXCLUSIVO ADMIN (Solo si tienes Rol ID 1) --}}
                        {{-- ==================================================== --}}
                        @if(auth()->user()->roles()->where('roles.id_rol', 1)->exists())
                            <li>
                                <a href="{{ route('admin.panel') }}" style="color: #ef4444; font-weight: bold;">
                                    Panel Admin
                                </a>
                            </li>
                        @endif

                        <li><a href="#">Configuración</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Cerrar Sesión</button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </header>

            <main>
                {{ $slot }}
            </main>
        </div>
        
        @stack('scripts')
    </body>
</html>