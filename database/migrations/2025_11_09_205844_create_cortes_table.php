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
        Schema::create('cortes', function (Blueprint $table) {
           $table->id('idCorte'); // Tu PK

            // Conexión a Users (quién hizo el corte)
            $table->foreignId('usuario_id')->constrained('users', 'id');

            $table->date('fecha');
            $table->decimal('monto_inicial', 10, 2); // typo corregido
            $table->decimal('monto_final', 10, 2);
            $table->decimal('monto_calculado', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cortes');
    }
};
