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
        Schema::create('ordenes', function (Blueprint $table) {
           $table->id('id_orden'); // Tu PK

            // Conexión a Mesas
            $table->foreignId('id_mesa')->constrained('mesas', 'id_mesa');

            // Conexión a Users (de Breeze)
            $table->foreignId('usuario_id')->constrained('users', 'id');

            $table->string('estado');
            $table->decimal('total', 10, 2);
            $table->integer('folioDia')->nullable(); // nullable por si no se usa
            $table->date('fecha');
            $table->text('nota')->nullable(); // text para notas largas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
