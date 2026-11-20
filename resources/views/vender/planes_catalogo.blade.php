@extends('maestra')
@section('titulo', 'Planes de suscripción')
@section('contenido')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Planes de suscripción</h1>
        <a class="btn btn-outline-primary" href="{{ route('vender.index') }}">Ver selección</a>
    </div>
    @include('notificacion')

    @isset($productoSeleccionado)
        <p class="mb-3">Software <strong>{{ $productoSeleccionado->nombre }}</strong></p>
    @endisset
    <form method="get" action="{{ route('planes.catalogo') }}" class="barra-filtros">
        @isset($productoSeleccionado)
            <input type="hidden" name="software_id" value="{{ $productoSeleccionado->id }}">
        @endisset
        @include('partials.segmento_catalogo', ['esFiltro' => true])
        <div class="form-group">
            <label for="frecuencia">Ciclo de cobro</label>
            <select class="form-select" id="frecuencia" name="frecuencia" data-no-select2>
                <option value="">Mensual y anual</option>
                @foreach(\App\Plan::FRECUENCIAS as $ciclo)
                    <option value="{{ $ciclo }}" @selected(request()->query('frecuencia') === $ciclo)>{{ ucfirst($ciclo) }}</option>
                @endforeach
            </select>
        </div>
        @include('planes.nivel', ['esFiltroNivel' => true])
        <div class="barra-filtros__acciones">
            <button type="submit" class="btn btn-catalogo-accion">Filtrar</button>
            <a class="btn btn-outline-secondary" href="{{ route('planes.catalogo') }}">Limpiar filtros</a>
        </div>
    </form>

    <div class="row">
        @forelse($planes as $plan)
            <div class="col-md-6 col-lg-4 mb-3">
                <article class="card h-100">
                    <div class="card-body">
                        <h2 class="h5">{{ $plan->nombre }}</h2>
                        @if($nivel = \App\Plan::NIVELES[$plan->nivel ?? ''] ?? null)
                            <span class="etiqueta-nivel-plan">{{ $nivel }}</span>
                        @endif
                        <p class="text-muted small">{{ \App\CatalogoSegmentos::etiqueta($plan->segmento_catalogo) }}</p>
                        <p>{{ $plan->descripcion }}</p>
                        <p class="mb-2">{{ $plan->productosSoftware->pluck('nombre')->join(', ') }}</p>
                        <p class="fw-bold">
                            @if($plan->precioAplicado() < $plan->precio)
                                <del class="text-muted">${{ number_format($plan->precio, 2, ',', '.') }}</del>
                            @endif
                            ${{ number_format($plan->precioAplicado(), 2, ',', '.') }} · {{ ucfirst($plan->frecuencia) }}
                        </p>
                        @if($plan->features)
                            <ul>
                                @foreach($plan->features as $caracteristica)
                                    <li>{{ $caracteristica }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <p>{{ $plan->cantidad_usuarios }} usuarios · {{ $plan->cantidad_dispositivos }} dispositivos</p>
                        <form method="post" action="{{ route('agregarProductoVenta') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $plan->id }}">
                            <button class="btn btn-primary">Seleccionar plan</button>
                        </form>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12"><p class="text-muted">Sin planes disponibles.</p></div>
        @endforelse
    </div>
@endsection
