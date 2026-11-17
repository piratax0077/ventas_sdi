@extends('maestra')
@section('titulo', 'Editar video de capacitación')
@section('contenido')
    <h1 class="h3">Editar video de capacitación</h1>
    @include('notificacion')
    <form method="post" action="{{ route('administracion.videos.update', $video) }}" enctype="multipart/form-data" style="max-width: 720px">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="titulo">Título</label>
            <input class="form-control" name="titulo" id="titulo" value="{{ old('titulo', $video->titulo) }}" required maxlength="160">
        </div>
        <div class="mb-3">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" name="descripcion" id="descripcion" rows="4" maxlength="2000">{{ old('descripcion', $video->descripcion) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="video">Reemplazar video (MP4)</label>
            <input class="form-control" type="file" name="video" id="video" accept="video/mp4,.mp4" aria-describedby="ayuda-video">
            <small id="ayuda-video" class="text-muted">Si reemplazas el MP4, adjunta la portada y los subtítulos del nuevo video en este formulario.</small>
        </div>
        <div class="mb-3">
            <label for="portada">Portada (JPG o PNG)</label>
            <input class="form-control" type="file" name="portada" id="portada" accept="image/jpeg,image/png">
        </div>
        <div class="mb-3">
            <label for="subtitulos">Subtítulos (VTT)</label>
            <input class="form-control" type="file" name="subtitulos" id="subtitulos" accept=".vtt,text/vtt">
        </div>
        <button class="btn btn-catalogo-accion">Guardar cambios</button>
        <a class="btn btn-outline-secondary" href="{{ route('administracion.videos.show', $video) }}">Cancelar</a>
    </form>
@endsection
