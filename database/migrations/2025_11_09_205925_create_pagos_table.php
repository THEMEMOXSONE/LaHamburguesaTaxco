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
        Schema::create('pagos', function (Blueprint $table) {
           $table->id('idPago'); // Tu PK

            // Conexión a Ordenes (ahora se ejecutará después de 'ordenes')
            $table->foreignId('id_orden')->constrained('ordenes', 'id_orden');

            $table->decimal('monto', 10, 2);
            $table->string('metodoPago');
            $table->date('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
