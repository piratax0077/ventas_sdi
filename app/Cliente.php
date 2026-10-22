<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'rol_id',
        'nombre',
        'rut',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'sexo',
        'prevision_id',
        'profesion',
        'especialidad',
        'sub_tipo_especialidad',
        'direccion',
        'numero_dir',
        'region_id',
        'ciudad_id',
        'telefono',
        'celular',
        'correo',
        // Institución
        'inst_rut',
        'nombre_institucion',
        'tipo_institucion',
        'inst_direccion',
        'inst_numero_dir',
        'inst_region_id',
        'inst_ciudad_id',
        'inst_telefono',
        // Responsable
        'responsable_rut',
        'responsable_nombre',
        'responsable_apellido_uno',
        'responsable_apellido_dos',
        'responsable_celular',
        'responsable_telefono',
        // Servicio
        'serv_rut',
        'nombre_servicio',
        'tipo_servicio',
        'serv_direccion',
        'serv_numero_dir',
        'serv_region_id',
        'serv_ciudad_id',
        'serv_telefono',
        // Referencias a otros modelos
        'id_usuario',
        // 'id_direccion',
        // 'correo_inst',
    ];

    protected $casts = ['fecha_nacimiento' => 'datetime'];

    // Relaciones
    public function rol()
    {
        return $this->belongsTo('App\Roles', 'rol_id');
    }

    public function prevision()
    {
        return $this->belongsTo('App\Prevision', 'prevision_id');
    }

    public function region()
    {
        return $this->belongsTo('App\Region', 'region_id');
    }
}

