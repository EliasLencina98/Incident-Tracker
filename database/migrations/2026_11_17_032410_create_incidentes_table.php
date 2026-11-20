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
        Schema::create('incidentes', function (Blueprint $table) {
            $table->id();   //automatico
            
            //Clasificacion del incidente 
            $table->date("fecha");
            $table->time("hora")->nullable();
            $table->string("tipo");
            $table->string("gravedad");
            $table->unsignedInteger("fatalidad")->default(0);
            $table->unsignedInteger("heridos")->default(0);

            //Ubicacion y condiciones del lugar
            $table->string("ubicacion");
            $table->decimal("latitud", 10, 8);      // Rango: -90.00000000 a 90.00000000
            $table->decimal("longitud", 11, 8);     // Rango: -180.00000000 a 180.00000000
            $table->string("estado_calzada")->nullable();
            $table->string("clima")->nullable();

            //Descripcion y seguimiento
            $table->text("descripcion");
            $table->string("causa_raiz");
            $table->text("accion_correlativa")->nullable();
            $table->string("estado")->default("pendiente");

            //Relaciones
            $table->foreignId("vehiculo_id")->constrained("vehiculo")->restrictOnDelete();
            $table->foreignId("chofer_id")->constrained("chofer")->restrictOnDelete();

            $table->timestamps(); //fecha de subida y modificacion
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidentes');
    }
};
