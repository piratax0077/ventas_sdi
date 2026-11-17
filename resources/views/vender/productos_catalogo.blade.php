@extends('maestra')
@section('titulo', 'Catálogo de software')
@section('contenido')
    <h1 class="h3 mb-3">Catálogo de software</h1>
    @include('notificacion')
    @include('partials.filtro_segmento')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h5 mb-0">Productos de software</h2>
        <a href="{{ route('planes.catalogo', request()->only('segmento')) }}">Ver todos los planes</a>
    </div>
    <div class="row">
        @forelse($software as $producto)
            <div class="col-md-6 col-lg-4 mb-3">
                <article class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h3 class="h5">{{ $producto->nombre }}</h3>
                        <p class="text-muted small">{{ \App\CatalogoSegmentos::etiqueta($producto->segmento_catalogo) }}</p>
                        <p>{{ $producto->descripcion }}</p>
                        <div class="mt-auto">
                            @if(!$producto->estado)
                                <span class="etiqueta-nivel-plan">En preparación</span>
                            @elseif($producto->planes_disponibles_count > 0)
                                <a class="btn btn-outline-primary" href="{{ route('planes.catalogo', ['software_id' => $producto->id]) }}">Ver planes</a>
                            @else
                                <small class="text-muted">Sin planes publicados</small>
                            @endif
                        </div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12"><p class="text-muted">No hay productos de software para este filtro.</p></div>
        @endforelse
    </div>
    {{ $software->links('pagination::bootstrap-5') }}
@endsection
