<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
        'tipo', // text, email, url, boolean, number, textarea
        'categoria', // general, empresa, email, sitio, etc
        'estado',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    /**
     * Obtener configuración por clave
     */
    public static function obtener($clave, $default = null)
    {
        $config = self::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }

    /**
     * Establecer configuración
     */
    public static function establecer($clave, $valor, $descripcion = null, $tipo = 'text', $categoria = 'general')
    {
        $config = self::where('clave', $clave)->first();

        if ($config) {
            $config->update(['valor' => $valor, 'descripcion' => $descripcion, 'tipo' => $tipo]);
        } else {
            self::create(compact('clave', 'valor', 'descripcion', 'tipo', 'categoria'));
        }

        return $config ?? self::where('clave', $clave)->first();
    }

    /**
     * Obtener todas las configuraciones de una categoría
     */
    public static function por_categoria($categoria)
    {
        return self::where('categoria', $categoria)
            ->where('estado', true)
            ->get()
            ->pluck('valor', 'clave')
            ->toArray();
    }
}
