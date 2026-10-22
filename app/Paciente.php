<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    //
    protected $table = 'pacientes';
    protected $fillable = [
        'token',
        'rut',
        'nombres',
        'apellido_uno',
        'apellido_dos',
        'fecha_nac',
        'sexo',
        'id_prevision',
        'telefono_uno',
        'telefono_dos',
        'id_usuario',
        'email',
        'id_direccion',
        'foto_perfil'
    ];
}
