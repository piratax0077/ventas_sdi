<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoCapacitacion extends Model
{
    protected $table = 'videos_capacitacion';
    protected $fillable = ['titulo', 'descripcion', 'video', 'portada', 'subtitulos'];
}
