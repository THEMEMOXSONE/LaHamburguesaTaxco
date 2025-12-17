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
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_prod'); // Tu PK
            $table->decimal('precio', 10, 2); // Decimal es mejor para dinero
            $table->string('descripcion'); // Corregí el typo 'descripc[i]on'
            $table->string('nombre');
            $table->timestamps(); // ¡Recomendado para seguimiento!
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
