<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
    protected $fillable = [ //asignacion masiva con Eloquent 
        "fecha",
        "tipo",
        "gravedad",
        "ubicacion",
        "latitud",
        "longitud",
        "descripcion",
        "causa_raiz",
        "accion_correlativa",
    ]; 
}
