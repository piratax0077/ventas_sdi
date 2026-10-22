<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    protected $table = 'mantenimientos';
    protected $fillable = [
        'descripcion',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'responsable',
        'notas'
    ];

    protected $casts = ['fecha_inicio' => 'datetime', 'fecha_fin' => 'datetime'];

    /**
     * Scopes
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Obtener etiqueta de estado
     */
    public function getEtiquetaEstadoAttribute()
    {
        $estilos = [
            'pendiente' => 'warning',
            'activo' => 'primary',
            'completado' => 'success',
            'cancelado' => 'danger'
        ];
        return $estilos[$this->estado] ?? 'secondary';
    }
}
