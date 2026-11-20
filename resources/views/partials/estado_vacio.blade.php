{{--
    Estado vacío: se muestra cuando una lista no tiene registros.
    Parámetros: titulo (obligatorio), mensaje, accionUrl, accionTexto,
    accionIcono (por defecto bi-plus-lg; null para no mostrar ícono),
    accionClase (por defecto btn-primary).
--}}
@php
    $accionIcono = array_key_exists('accionIcono', get_defined_vars()) ? $accionIcono : 'bi-plus-lg';
    $accionClase = $accionClase ?? 'btn-primary';
@endphp
<div class="empty-state" role="status">
    <div class="empty-state-icon" aria-hidden="true">
        <i class="bi bi-exclamation-circle"></i>
    </div>
    <h5 class="empty-state-titulo">{{ $titulo }}</h5>
    @isset($mensaje)
        <p class="empty-state-texto">{{ $mensaje }}</p>
    @endisset
    @isset($accionUrl)
        <a href="{{ $accionUrl }}" class="btn {{ $accionClase }}">
            @if($accionIcono)<i class="bi {{ $accionIcono }}" aria-hidden="true"></i>@endif
            {{ $accionTexto }}
        </a>
    @endisset
</div>
