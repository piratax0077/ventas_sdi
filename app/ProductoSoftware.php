<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoSoftware extends Model
{
    public const TIPOS = [
        'api' => 'API/Servicio',
        'mobile' => 'App Móvil',
        'web' => 'Aplicación Web',
        'desktop' => 'Desktop',
        'otro' => 'Otro',
    ];

    protected $table = 'productos_software';

    protected $fillable = [
        'nombre',
        'segmento_catalogo',
        'descripcion',
        'versión',
        'tipo',
        'tipos',
        'url_descarga',
        'estado',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'tipos' => 'array',
    ];

    public function tiposSeleccionados(): array
    {
        return $this->tipos ?? ($this->tipo ? [$this->tipo] : []);
    }

    public function nombresDeTipos(): array
    {
        return array_map(fn (string $tipo) => self::TIPOS[$tipo] ?? $tipo, $this->tiposSeleccionados());
    }

    public function planes()
    {
        return $this->belongsToMany('App\Plan', 'plan_producto_software', 'id_producto_software', 'id_plan');
    }
}
