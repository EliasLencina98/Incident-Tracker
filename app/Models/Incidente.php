<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
    protected $fillable = [
        "fecha",
        "hora", /** nullable */
        "tipo", /** nullable */
        "gravedad", 
        "fatalidad",
        "heridos", 
        "ubicacion",
        "latitud",
        "longitud",
        "estado_calzada", /** nullable */
        "clima", /** nullable */
        "descripcion", /** nullable */
        "causa_raiz", /** nullable */
        "accion_correlativa", /** nullable */
        "estado",  /** default pendiente */
        "vehiculo_id",
        "chofer_id",
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function chofer()
    {
        return $this->belongsTo(Chofer::class);
    }

    public function responsables()
    {
        return $this->belongsToMany(Usuario::class, 'responsables_de_investigacion', 'incidente_id', 'usuario_id')
                    ->withPivot('fecha_asignacion')
                    ->withTimestamps();
    }

    public static function calcularGravedad(string $tipo, int $heridos, int $fatalidad): string
    {
        // Regla absoluta, si hay fatalidad, retorna gravedad fatal. para luego guardarla en la BD
        if ($fatalidad >= 1) {
            return 'fatal';
        }

       
        $columnaHeridos = $heridos >= 2 ? 2 : $heridos;

        // Matriz. arreglo asociativo de 2 dimensiones
        $matriz = [
            'colision/choque vehicular' => [0 => 'moderado', 1 => 'grave', 2 => 'grave'],
            'impacto objeto fijo'       => [0 => 'leve',     1 => 'moderado', 2 => 'grave'],
            'vuelco'                    => [0 => 'moderado', 1 => 'grave', 2 => 'grave'],
            'despiste'                  => [0 => 'moderado', 1 => 'grave', 2 => 'grave'],
            'atropello'                 => [0 => 'grave',    1 => 'grave', 2 => 'grave'], 
            'falla mecanica'            => [0 => 'leve',     1 => 'grave', 2 => 'grave'],
            'principio de incendio/incendio' => [0 => 'moderado', 1 => 'grave', 2 => 'grave'],
            'daño menor'                => [0 => 'leve',     1 => 'moderado', 2 => 'grave'],
            'casi incidente'            => [0 => 'leve',     1 => 'moderado', 2 => 'grave'],
        ];

        // Retornar el valor de la matriz con el tipo y la columna de heridos calulcada, y como valor defecto leve
        return $matriz[$tipo][$columnaHeridos] ?? 'leve';
    }
}
