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
        $table->time('hora')->nullable()->after('fecha');
        $table->boolean('fatalidad')->default(false)->after('hora');
        $table->integer('heridos')->default(0)->after('fatalidad');
        $table->string('estado_calzada')->nullable()->after('longitud');
        $table->string('clima')->nullable()->after('impacto_economico');

        $table->foreignId('vehiculo_id')->after('clima')->constrained('vehiculos')->onDelete('cascade');
        $table->foreignId('chofer_id')->after('vehiculo_id')->constrained('choferes')->onDelete('cascade');
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
