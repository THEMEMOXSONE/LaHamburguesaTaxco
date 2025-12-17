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
        Schema::create('mesas', function (Blueprint $table) {
            $table->id('id_mesa'); // Tu PK
            $table->string('nombre');
            $table->integer('comensales'); // De tu ERD (capacidad)
            $table->string('estado')->default('disponible'); // Poner un valor por defecto es útil
            $table->timestamps(); // ¡Recomendado para seguimiento!
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
