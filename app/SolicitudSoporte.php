<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudSoporte extends Model
{
    protected $table = 'solicitudes_soporte';

    protected $fillable = [
        'usuario_id',
        'tipo_solicitud',
        'asunto',
        'descripcion',
        'prioridad',
        'estado',
        'numero_ticket',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const TIPOS_SOLICITUD = [
        'problema_tecnico',
        'consulta_general',
        'reporte_error',
        'solicitud_feature',
        'acceso_cuenta',
        'facturacion',
        'otros',
    ];

    const PRIORIDADES = ['baja', 'media', 'alta', 'urgente'];

    const ESTADOS    = ['abierta', 'en_progreso', 'resuelta', 'cerrada'];

    // ─── Relaciones ───────────────────────────────────────────────────────────

    public function usuario()
    {
        return $this->belongsTo('App\Models\User', 'usuario_id');
    }

    /** Archivos adjuntos de la solicitud inicial (sin respuesta_id) */
    public function archivos()
    {
        return $this->hasMany('App\ArchivoSoporte', 'solicitud_id')
                    ->whereNull('respuesta_id');
    }

    public function respuestas()
    {
        return $this->hasMany('App\Models\RespuestaTicket', 'solicitud_id')
                    ->orderBy('created_at', 'asc');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopeAbiertas($query)
    {
        return $query->whereIn('estado', ['abierta', 'en_progreso']);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function estaAbierta(): bool
    {
        return in_array($this->estado, ['abierta', 'en_progreso']);
    }

    public function estaResuelta(): bool
    {
        return in_array($this->estado, ['resuelta', 'cerrada']);
    }

    // ─── Boot ────────────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                $ticket = 'TKT-' . date('Ymd') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            } while (static::where('numero_ticket', $ticket)->exists());

            $model->numero_ticket = $ticket;
        });
    }
}
