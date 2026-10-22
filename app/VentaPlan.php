<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentaPlan extends Model
{
    protected $table = 'venta_planes';
    protected $fillable = [
        'id_venta',
        'id_plan',
        'precio_unitario',
        'cantidad',
        'subtotal',
        'fecha_inicio',
        'fecha_vencimiento',
        'estado',
        'created_at',
        'updated_at'
    ];

    protected $casts = ['fecha_inicio' => 'datetime', 'fecha_vencimiento' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function venta()
    {
        return $this->belongsTo('App\Venta', 'id_venta');
    }

    public function plan()
    {
        return $this->belongsTo('App\Plan', 'id_plan');
    }
}
