<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
    protected $fillable = [
        "fecha",
        "hora",
        "tipo",
        "gravedad",
        "fatalidad",
        "heridos",
        "ubicacion",
        "latitud",
        "longitud",
        "estado_calzada",
        "clima",
        "descripcion",
        "causa_raiz",
        "accion_correlativa",
        "impacto_economico",
        "estado",
        "vehiculo_id",
        "chofer_id",
    ];

    // Relación 1:N (Un incidente pertenece a un vehículo)
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    // Relación 1:N (Un incidente pertenece a un chofer)
    public function chofer()
    {
        return $this->belongsTo(Chofer::class);
    }

    // Relación M:N (Un incidente tiene varios responsables de investigación)
    public function responsables()
    {
        return $this->belongsToMany(Usuario::class, 'responsables_de_investigacion', 'incidente_id', 'usuario_id')
                    ->withPivot('fecha_asignacion')
                    ->withTimestamps();
    }
}
