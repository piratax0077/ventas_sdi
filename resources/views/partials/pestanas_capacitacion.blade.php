<nav class="nav nav-tabs mb-3" aria-label="Línea de capacitación">
    @foreach(\App\VideoCapacitacion::LINEAS as $clave => $nombre)
        <a class="nav-link {{ $linea === $clave ? 'active' : '' }}"
           href="{{ route($rutaPestanas, ['linea' => $clave]) }}"
           @if($linea === $clave) aria-current="page" @endif>{{ $nombre }}</a>
    @endforeach
</nav>
