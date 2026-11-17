@extends('maestra')
@section('titulo', 'Contactar cliente')
@section('contenido')
    <h1 class="h3">Contactar a {{ $cliente->nombre }} {{ $cliente->primer_apellido }}</h1>
    @include('notificacion')

    @if($correo || $whatsapp)
        @include('seguimientos.modelos_mensaje')
    @endif
    <div class="d-flex flex-wrap gap-2 mb-3">
        @if($correo)
            <a id="contacto-correo" class="btn btn-outline-primary" href="mailto:{{ rawurlencode($correo) }}">Abrir correo</a>
        @endif
        @if($whatsapp)
            <a id="contacto-whatsapp" class="btn btn-outline-success" href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener noreferrer">Abrir WhatsApp</a>
        @endif
        <a class="btn btn-link" href="{{ route('clientes.index') }}">Volver a clientes</a>
    </div>
    @if($correo || $whatsapp)
        <form method="post" action="{{ route('clientes.contacto.store', $cliente) }}" class="d-flex flex-wrap align-items-end gap-2 mb-4">
            @csrf
            <input type="hidden" name="lote" value="{{ old('lote', $lote) }}">
            <div>
                <label for="canal">Canal del seguimiento</label>
                <select id="canal" name="canal" class="form-select" data-no-select2>
                    @if($correo)<option value="correo">Correo electrónico</option>@endif
                    @if($whatsapp)<option value="whatsapp">WhatsApp</option>@endif
                </select>
            </div>
            <button class="btn btn-catalogo-accion">Registrar seguimiento</button>
        </form>
    @else
        <p class="text-muted">Este cliente no tiene un correo ni un móvil válido para contacto.</p>
    @endif
    <h2 class="h5">Historial de contacto</h2>
    @include('seguimientos.tabla')
@endsection
