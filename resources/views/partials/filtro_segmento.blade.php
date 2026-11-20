<form method="get" action="{{ url()->current() }}" class="barra-filtros">
    @include('partials.segmento_catalogo', ['esFiltro' => true])
    <div class="barra-filtros__acciones">
        <button class="btn btn-catalogo-accion" type="submit">Filtrar</button>
        @if(request()->filled('segmento'))
            <a class="btn btn-outline-secondary" href="{{ url()->current() }}">Limpiar filtro</a>
        @endif
    </div>
</form>
