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

            $table->date("fecha");
            $table->string("tipo");
            $table->string("gravedad");
            $table->string("ubicacion")->nullable();    //puede ser NULL (investigacion en curso)

            $table->decimal("latitud", 10, 8);      // Rango: -90.00000000 a 90.00000000
            $table->decimal("longitud", 11, 8);     // Rango: -180.00000000 a 180.00000000

            $table->text("descripcion");
            $table->string("causa_raiz");
            $table->text("accion_correlativa")->nullable();     //puede ser NULL (investigacion en curso)

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
