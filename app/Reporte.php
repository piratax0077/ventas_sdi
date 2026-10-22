<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $table = 'reportes';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo', // mensual, trimestral, anual, personalizado
        'frecuencia', // diaria, semanal, mensual, trimestral, anual
        'destinatarios', // emails separados por coma
        'contenido', // Template HTML del reporte
        'estado',
        'ultima_ejecucion',
        'proxima_ejecucion'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'ultima_ejecucion' => 'datetime',
        'proxima_ejecucion' => 'datetime'
    ];

    /**
     * Obtener reportes activos
     */
    public static function activos()
    {
        return self::where('estado', true)->get();
    }

    /**
     * Obtener reportes por tipo
     */
    public static function porTipo($tipo)
    {
        return self::where('tipo', $tipo)->get();
    }

    /**
     * Obtener reportes por frecuencia
     */
    public static function porFrecuencia($frecuencia)
    {
        return self::where('frecuencia', $frecuencia)->get();
    }

    /**
     * Obtener lista de destinatarios como array
     */
    public function obtenerDestinatarios()
    {
        return array_filter(array_map('trim', explode(',', (string) $this->destinatarios)));
    }

    /**
     * Establecer destinatarios desde array
     */
    public function establecerDestinatarios($emails)
    {
        $this->destinatarios = is_array($emails) ? implode(',', $emails) : $emails;
    }

    /**
     * Marcar como ejecutado
     */
    public function marcarEjecutado($proximo_fecha = null)
    {
        $this->ultima_ejecucion = now();
        if ($proximo_fecha) {
            $this->proxima_ejecucion = $proximo_fecha;
        }
        $this->save();
    }
}
