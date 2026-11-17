<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Plan extends Model
{
    public const FRECUENCIAS = ['mensual', 'anual'];
    public const NIVELES = [
        'basico' => 'Básico',
        'gold' => 'Gold',
        'premium' => 'Premium',
        'otro' => 'Otro',
    ];
    protected $table = 'planes';
    protected $fillable = [
        'nombre',
        'nivel',
        'descripcion',
        'precio',
        'precio_promocional',
        'segmento_catalogo',
        'frecuencia',
        'cantidad_usuarios',
        'cantidad_dispositivos',
        'features', // JSON con características
        'estado',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'features' => 'array',
        'estado' => 'boolean'
    ];

    public function precioAplicado(): float
    {
        return min($this->precio, $this->precio_promocional ?? $this->precio);
    }

    public function scopeDisponible(Builder $query): Builder
    {
        return $query->where('estado', true)
            ->whereIn('frecuencia', self::FRECUENCIAS)
            ->whereHas('productosSoftware', fn (Builder $software) => $software->where('productos_software.estado', true));
    }

    public function ventaPlanes()
    {
        return $this->hasMany('App\VentaPlan', 'id_plan');
    }

    public function productosSoftware()
    {
        return $this->belongsToMany('App\ProductoSoftware', 'plan_producto_software', 'id_plan', 'id_producto_software');
    }
}
