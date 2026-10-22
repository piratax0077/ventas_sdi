<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivoSoporte extends Model
{
    protected $table = 'archivos_soporte';

    protected $fillable = [
        'solicitud_id',
        'respuesta_id',
        'nombre_original',
        'nombre_guardado',
        'ruta',
        'tipo_mime',
        'tamaño',
    ];

    // Relaciones
    public function solicitud()
    {
        return $this->belongsTo('App\SolicitudSoporte', 'solicitud_id');
    }
}
