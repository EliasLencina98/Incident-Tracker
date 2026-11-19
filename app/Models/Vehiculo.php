<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculo';
    protected $fillable = ['patente', 'modelo', 'anio', 'tipo_vehiculo'];

    public function incidentes()
    {
        return $this->hasMany(Incidente::class);
    }
}
