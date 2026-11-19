@extends('maestra')
@section('titulo', 'Ver video de capacitación')
@section('contenido')
    <h1 class="h3">{{ $video->titulo }}</h1>
    @include('notificacion')
    @if($video->descripcion)<p>{{ $video->descripcion }}</p>@endif
    <div class="mb-3" style="max-width: 960px">
        @include('administracion.videos.reproductor')
    </div>
    <a class="btn btn-catalogo-accion" href="{{ route('administracion.videos.edit', $video) }}">Editar video</a>
    <a class="btn btn-outline-secondary" href="{{ route('administracion.videos.index', ['linea' => $video->linea]) }}">Volver a videos</a>
@endsection
