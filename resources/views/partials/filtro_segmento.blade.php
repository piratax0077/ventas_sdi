<form method="get" action="{{ url()->current() }}" class="d-flex flex-wrap align-items-center mb-3">
    @include('partials.segmento_catalogo', ['esFiltro' => true])
    <button class="btn btn-catalogo-accion ml-2 ms-2" type="submit">Filtrar</button>
    @if(request()->filled('segmento'))
        <a class="btn btn-outline-secondary ml-2 ms-2" href="{{ url()->current() }}">Limpiar filtro</a>
    @endif
</form>
