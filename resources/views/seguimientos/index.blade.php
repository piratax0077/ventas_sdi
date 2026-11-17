@extends('maestra')
@section('titulo', 'Control de respuestas')
@section('contenido')
    <h1 class="h3">Control de respuestas</h1>
    @include('notificacion')
    <form method="get" class="d-flex flex-wrap align-items-end gap-2 mb-3">
        <div>
            <label for="estado">Estado</label>
            <select id="estado" name="estado" class="form-select" data-no-select2>
                <option value="">Todos los estados</option>
                @foreach(\App\SeguimientoContacto::ESTADOS as $valor => $etiqueta)
                    <option value="{{ $valor }}" @selected($estado === $valor)>{{ $etiqueta }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-catalogo-accion">Filtrar</button>
        <a class="btn btn-outline-primary" href="{{ route('clientes.index') }}">Buscar clientes</a>
    </form>
    @include('seguimientos.tabla')
@endsection
