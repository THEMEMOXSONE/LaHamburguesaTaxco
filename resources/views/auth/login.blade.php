@push('styles')
    @vite(['resources/css/login.css'])
@endpush

@push('scripts')
    @vite(['resources/js/login.js'])
@endpush


<x-guest-layout>
    
    <div class="login-container">
        
        <h2 class="login-title">Iniciar Sesión</h2>
        <p class="login-subtitle">Acceso al sistema de comandas.</p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf 
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="error-message" />
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="error-message" />
            </div>

            <div class="form-group-inline">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Recordar sesión</label>
            </div>

            <div class="form-actions">
                @if (Route::has('password.request'))
                    <a class="forgot-password" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
                
                {{-- AQUÍ ELIMINAMOS EL ENLACE DE REGISTRO --}}
                {{-- Solo el admin puede crear usuarios desde dentro --}}

                <button type="submit" class="login-button">
                    Entrar
                </button>
            </div>
        </form>

    </div>
</x-guest-layout>