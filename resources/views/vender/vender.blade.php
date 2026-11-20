@extends('maestra')
@section('titulo', 'Registrar suscripción')
@section('contenido')
    <h1 class="h3 mb-3">Registrar suscripción</h1>
    @include('notificacion')
    <form method="get" action="{{ route('clientes.index') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
        <div>
            <label for="buscar-contacto">Buscar cliente para contactar</label>
            <input class="form-control" id="buscar-contacto" name="buscar" type="search" placeholder="Nombre, correo o teléfono">
        </div>
        <button class="btn btn-catalogo-accion">Buscar clientes</button>
        <a class="btn btn-outline-primary" href="{{ route('seguimientos.index') }}">Control de respuestas</a>
        <a class="btn btn-outline-primary" href="{{ route('vender.videos') }}">Videos para vendedores</a>
    </form>

    <a class="btn btn-primary mb-3" href="{{ route('planes.catalogo') }}">Elegir un plan</a>
    @if($carrito->isEmpty())
        <p class="text-muted">Elige un plan mensual o anual del catálogo.</p>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Plan</th><th>Ciclo</th><th>Cantidad</th><th>Precio por ciclo</th><th>Subtotal</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($carrito as $indice => $item)
                        @php($plan = $planes->get($item->id))
                        <tr>
                            <td>{{ $plan?->nombre ?? $item->nombre }}</td>
                            <td>{{ ucfirst($plan?->frecuencia ?? $item->frecuencia) }}</td>
                            <td>{{ $item->cantidad }}</td>
                            @if($plan)
                                <td>${{ number_format($plan->precioAplicado(), 2, ',', '.') }}</td>
                                <td>${{ number_format($plan->precioAplicado() * $item->cantidad, 2, ',', '.') }}</td>
                            @else
                                <td colspan="2" class="text-danger">Plan no disponible</td>
                            @endif
                            <td>
                                <form method="post" action="{{ route('quitarProductoDeVenta') }}">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="indice" value="{{ $indice }}">
                                    <button class="btn btn-outline-danger btn-sm">Quitar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="fw-bold">Total del período inicial ${{ number_format($total, 2, ',', '.') }}</p>
        <form method="post" action="{{ route('terminarOCancelarVenta') }}">
            @csrf
            <div class="form-group mb-3">
                <label for="id_cliente">Cliente</label>
                <select class="form-control" id="id_cliente" name="id_cliente" required>
                    <option value="">Seleccionar cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" @selected(old('id_cliente') == $cliente->id)>
                            {{ $cliente->nombre }} {{ $cliente->primer_apellido }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if($clientes->isEmpty())
                <a class="btn btn-link" href="{{ route('clientes.create') }}">Crear cliente</a>
            @endif
            <button name="accion" value="terminar" class="btn btn-success" @disabled($clientes->isEmpty() || $planes->count() !== $carrito->unique('id')->count())>
                Registrar suscripción
            </button>
            <button name="accion" value="cancelar" class="btn btn-outline-secondary" formnovalidate>Vaciar selección</button>
        </form>
    @endif
@endsection
