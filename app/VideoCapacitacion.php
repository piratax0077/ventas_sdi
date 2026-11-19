<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoCapacitacion extends Model
{
    public const LINEAS = ['medichile' => 'Medichile', 'veterchile' => 'Veterchile'];

    protected $table = 'videos_capacitacion';
    protected $fillable = ['titulo', 'descripcion', 'video', 'portada', 'subtitulos', 'linea'];

    public function demostracion(): ?array
    {
        $slug = preg_replace('/^presentacion-/', '', pathinfo($this->video, PATHINFO_FILENAME));
        return config('demostraciones_videos')[$slug] ?? null;
    }

    public function explicacion(): ?array
    {
        $slug = preg_replace('/^presentacion-/', '', pathinfo($this->video, PATHINFO_FILENAME));
        return config('explicaciones_veterchile')[$slug] ?? null;
    }
}
