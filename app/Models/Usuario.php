<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = ['dni', 'nombre', 'rol'];

    public function incidentesInvestigados()
    {
        return $this->belongsToMany(Incidente::class, 'responsables_de_investigacion', 'usuario_id', 'incidente_id')
                    ->withPivot('fecha_asignacion')
                    ->withTimestamps();
    }
}
