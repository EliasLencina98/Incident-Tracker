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
            $table->string("ubicacion");

            $table->decimal("latitud", 10, 8);      // Rango: -90.00000000 a 90.00000000
            $table->decimal("longitud", 11, 8);     // Rango: -180.00000000 a 180.00000000

            $table->text("descripcion");
            $table->string("causa_raiz")->nullable()->change();
            $table->text("accion_correlativa")->nullable()->change();
            $table->string('estado')->default('pendiente')->after('accion_correlativa');    // valor por defecto pendiente

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


// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
//     {
//         // Usamos Schema::table porque la tabla ya existe
//         Schema::table('incidentes', function (Blueprint $table) {
//             // Modificamos los campos existentes para que sean nullable
//             $table->string("causa_raiz")->nullable()->change();
//             $table->text("accion_correlativa")->nullable()->change();

//             // Agregamos el nuevo campo si todavía no lo tenías
//             if (!Schema::hasColumn('incidentes', 'estado')) {
//                 $table->string('estado')->default('pendiente')->after('accion_correlativa');
//             }
//         });
//     }

//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::table('incidentes', function (Blueprint $table) {
//             // Revertir los cambios si fuera necesario
//             $table->string("causa_raiz")->nullable(false)->change();
//             $table->text("accion_correlativa")->nullable(false)->change();
            
//             if (Schema::hasColumn('incidentes', 'estado')) {
//                 $table->dropColumn('estado');
//             }
//         });
//     }
// };