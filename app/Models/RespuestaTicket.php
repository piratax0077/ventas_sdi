<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespuestaTicket extends Model
{
    protected $table = 'respuestas_tickets';

    protected $fillable = [
        'solicitud_id',
        'usuario_id',
        'contenido',
        'estado_nuevo',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─── Relaciones ───────────────────────────────────────────────────────────

    public function solicitud()
    {
        return $this->belongsTo('App\SolicitudSoporte', 'solicitud_id');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\User', 'usuario_id');
    }

    /** Archivos adjuntos de esta respuesta específica */
    public function archivos()
    {
        return $this->hasMany('App\ArchivoSoporte', 'respuesta_id');
    }
}
