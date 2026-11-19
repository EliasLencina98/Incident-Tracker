<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chofer extends Model
{
    protected $table = 'chofer';
    protected $fillable = ['dni', 'nombre', 'fecha_nacimiento', 'fecha_ingreso', 'puesto', 'licencia'];

    public function incidentes()
    {
        return $this->hasMany(Incidente::class);
    }
}
