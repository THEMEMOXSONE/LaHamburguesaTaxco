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
        Schema::create('detalle_orden', function (Blueprint $table) {
           $table->id('id_detalle'); // Tu PK

            // Conexión a Ordenes
            $table->foreignId('id_orden')->constrained('ordenes', 'id_orden');

            // Conexión a Productos
            $table->foreignId('id_prod')->constrained('productos', 'ID_prod');

            $table->integer('cantidad');
            $table->decimal('precio', 10, 2); // El precio al que se vendió
            $table->text('notas')->nullable();
            $table->timestamps(); // ¡Recomendado para seguimiento!
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_orden');
    }
};
