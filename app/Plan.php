<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'planes';
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'frecuencia', // mensual, trimestral, anual
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

    public function ventaPlanes()
    {
        return $this->hasMany('App\VentaPlan', 'id_plan');
    }

    public function productosSoftware()
    {
        return $this->belongsToMany('App\ProductoSoftware', 'plan_producto_software', 'id_plan', 'id_producto_software');
    }
}
