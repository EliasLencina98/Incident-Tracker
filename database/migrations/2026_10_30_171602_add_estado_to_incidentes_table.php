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
        Schema::table('incidentes', function (Blueprint $table) {
        // valor por defecto 'pendiente'
        $table->string('estado')->default('pendiente')->after('accion_correlativa');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidentes', function (Blueprint $table) {
            //
        });
    }

    
};
