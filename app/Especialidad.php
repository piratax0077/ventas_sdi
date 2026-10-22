<?php

namespace App;
use App\TipoEspecialidad;
use App\Profesional;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    //
    protected $table = 'especialidades';

    public function TipoEspecialidad()
    {
        return $this->hasOne(TipoEspecialidad::class,'id_tipo_especialidad');
    }

    public function Profesionales()
    {
        return $this->hasMany(Profesional::class, 'id_especialidad', 'id');
    }
}
