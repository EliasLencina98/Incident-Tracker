<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('responsables_de_investigacion', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
        });

        Schema::table('responsables_de_investigacion', function (Blueprint $table) {
            $table->foreign('usuario_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        throw new \RuntimeException('No se restaura la referencia anterior: la tabla usuarios no existe.');
    }
};
