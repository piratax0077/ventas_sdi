<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instituciones extends Model
{
    //
    protected $table = 'instituciones';
    protected $fillable = [
        'nombre',
        'razon_social',
        'rut',
        'id_tipo_institucion',
        'id_lugar_atencion',
        'direccion',
        'telefono',
        'email',
        'id_responsable',
        'id_responsable_subdireccion',
        'id_responsable_farmacia',
        'id_direccion',
        'foto_perfil'
    ];
}
