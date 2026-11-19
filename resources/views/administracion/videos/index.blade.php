@extends('maestra')
@section('titulo', 'Administración de videos')
@section('contenido')
    <h1 class="h3 mb-3">Administración de videos</h1>
    @include('notificacion')
    @include('partials.pestanas_capacitacion', ['rutaPestanas' => 'administracion.videos.index'])
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Video</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse($videos as $video)
                    <tr>
                        <td>
                            <strong>{{ $video->titulo }}</strong>
                            <small class="d-block text-muted">{{ $video->descripcion }}</small>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-outline-primary" href="{{ route('administracion.videos.show', $video) }}">Ver</a>
                                <a class="btn btn-outline-primary" href="{{ route('administracion.videos.edit', $video) }}">Editar</a>
                                <form method="post" action="{{ route('administracion.videos.destroy', $video) }}"
                                      onsubmit="return confirm('¿Eliminar este video de la capacitación?')">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-outline-danger">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="2">Sin videos de capacitación.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $videos->links('pagination::bootstrap-5') }}
    <a class="btn btn-outline-secondary" href="{{ route('vender.videos', ['linea' => $linea]) }}">Ver sección de vendedores</a>
@endsection
