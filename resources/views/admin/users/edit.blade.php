<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modificar Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        {{-- Nombre --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                Nombre Completo
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" name="name" value="{{ $user->name }}" required>
                        </div>

                        {{-- Llave (Email) - BLOQUEADO --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                                Email (Campo Llave - No editable)
                            </label>
                            {{-- Usamos readonly y un fondo gris para indicar que no se toca --}}
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-500 bg-gray-200 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" name="email" value="{{ $user->email }}" readonly>
                        </div>

                        {{-- Password (Opcional) --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                                Nueva Contraseña (Dejar en blanco para mantener la actual)
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password">
                        </div>

                        {{-- Rol --}}
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                                Rol Asignado
                            </label>
                            <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="role" name="role">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id_rol }}" {{ $user->hasRole($role->nombrerol) ? 'selected' : '' }}>
                                        {{ $role->nombrerol }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-between">
                            <button class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Actualizar Registro
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Cancelar
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>