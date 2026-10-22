<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoSoftware extends Model
{
    protected $table = 'productos_software';

    protected $fillable = [
        'nombre',
        'descripcion',
        'versión',
        'tipo', // web, desktop, mobile, etc
        'url_descarga',
        'estado',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    public function planes()
    {
        return $this->belongsToMany('App\Plan', 'plan_producto_software', 'id_producto_software', 'id_plan');
    }
}
