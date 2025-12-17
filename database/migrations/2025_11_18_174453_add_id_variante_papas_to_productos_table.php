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
        Schema::table('productos', function (Blueprint $table) {
         // Esta columna guardará el ID del producto "con papas"
        // Si es NULL, significa que este producto no tiene variante con papas
        $table->unsignedBigInteger('id_variante_papas')->nullable()->after('ID_prod');   //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('id_variante_papas');
        });
    }
};
