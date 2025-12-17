<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuario_rol', function (Blueprint $table) {
            // Conexión a Users
            $table->foreignId('usuario_id')->constrained('users', 'id');
            
            // Conexión a Roles
            $table->foreignId('id_rol')->constrained('roles', 'id_rol');

            // Definimos que la combinación de ambas es la clave primaria
            $table->primary(['usuario_id', 'id_rol']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_rol');
    }
};
