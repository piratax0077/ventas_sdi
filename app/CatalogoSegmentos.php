<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class CatalogoSegmentos
{
    public const LINEAS = [
        'medichile' => ['Medichile', [
            'asistente-online' => 'Asistente Online',
            'centros-medicos' => 'Centros médicos',
            'clinicas' => 'Clínicas',
            'hospitales' => 'Hospitales',
            'laboratorios' => 'Laboratorios',
            'pacientes' => 'Pacientes',
            'profesionales' => 'Profesionales',
        ]],
        'veterchile' => ['Veterchile', [
            'asistente-online' => 'Asistente Online',
            'centros-veterinarios' => 'Centros veterinarios',
            'clinicas' => 'Clínicas veterinarias',
            'hospitales' => 'Hospitales veterinarios',
            'laboratorios' => 'Laboratorios veterinarios',
            'profesionales' => 'Profesionales veterinarios',
            'tutores' => 'Tutores',
        ]],
        'alimentos' => ['Alimentos', ['general' => 'General']],
        'farmacias' => ['Farmacias', ['general' => 'General']],
    ];

    public static function segmentos(): array
    {
        $segmentos = [];
        foreach (self::LINEAS as $linea => [$nombre, $publicos]) {
            foreach ($publicos as $publico => $etiqueta) {
                $segmentos["$linea.$publico"] = "$nombre · $etiqueta";
            }
        }
        return $segmentos;
    }

    public static function reglas(): array
    {
        return ['nullable', 'string', Rule::in(array_keys(self::segmentos()))];
    }

    public static function etiqueta(?string $segmento): string
    {
        return self::segmentos()[$segmento ?? ''] ?? 'Sin clasificar';
    }

    public static function filtrar(Builder $consulta, mixed $filtro): Builder
    {
        if ($filtro === null || $filtro === '') {
            return $consulta;
        }
        if ($filtro === 'sin-clasificar') {
            return $consulta->whereNull('segmento_catalogo');
        }
        abort_unless(is_string($filtro) && (isset(self::LINEAS[$filtro]) || isset(self::segmentos()[$filtro])), 400);

        return isset(self::LINEAS[$filtro])
            ? $consulta->where('segmento_catalogo', 'like', $filtro.'.%')
            : $consulta->where('segmento_catalogo', $filtro);
    }
}
