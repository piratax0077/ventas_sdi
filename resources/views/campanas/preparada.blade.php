@extends('maestra')
@section('titulo', 'Mensajes preparados')
@section('contenido')
    <h1 class="h3">Mensajes preparados</h1>
    <p>Abre el mensaje, revísalo y envíalo desde tu aplicación de {{ $canal === 'whatsapp' ? 'WhatsApp' : 'correo' }}.</p>
    @if($omitidos > 0)
        <p class="text-muted">Contactos duplicados o sin datos válidos omitidos: {{ $omitidos }}.</p>
    @endif
    @if($correoGrupal)
        <a class="btn btn-catalogo-accion mb-3" href="{{ $correoGrupal }}">Abrir correo grupal con copia oculta</a>
    @endif
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Cliente</th><th>Contacto</th><th>Mensaje</th></tr></thead>
            <tbody>
            @foreach($destinatarios as $fila)
                <tr>
                    <td>{{ $fila['cliente']->nombre }} {{ $fila['cliente']->primer_apellido }}</td>
                    <td>{{ $fila['contacto'] }}</td>
                    <td>
                        <a href="{{ $fila['enlace'] }}" class="btn btn-outline-primary" @if($canal === 'whatsapp') target="_blank" rel="noopener noreferrer" @endif>
                            {{ $canal === 'whatsapp' ? 'Abrir WhatsApp' : 'Abrir correo' }}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <a class="btn btn-outline-primary" href="{{ route('seguimientos.index') }}">Control de respuestas</a>
    <a class="btn btn-outline-secondary" href="{{ route('campanas.index', ['canal' => $canal]) }}">Volver a campañas</a>
@endsection
