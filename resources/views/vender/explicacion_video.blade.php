<section class="mt-3 border rounded p-3" aria-labelledby="explicacion-{{ $video->id }}">
    <h3 class="h6" id="explicacion-{{ $video->id }}">{{ $explicacion['titulo'] }}</h3>
    <p class="small">{{ $explicacion['introduccion'] }}</p>
    <p class="small text-muted">Selecciona una función para revisar cómo explicarla y qué ventaja aporta.</p>
    {{-- Acordeón (estilos en "VIDEOS PARA VENDEDORES · ACORDEÓN DE VENTAJAS" de main.css).
         Cada función puede ser [titulo, descripcion, ventaja] o el formato extendido con claves
         trata, ventajas, necesidad, partir y persuadir (ver config/explicaciones_veterchile.php).
         Opcionales: grupo (título que agrupa y reinicia la numeración), trata_lista, etiqueta_trata,
         etiqueta_ventajas, a_quien e importante. --}}
    <div class="ventajas-acordeon">
        @php $numero = 0; @endphp
        @foreach($explicacion['funciones'] as $funcion)
            @php
                $extendida = isset($funcion['titulo']);
                $titulo = $extendida ? $funcion['titulo'] : $funcion[0];
                if (!empty($funcion['grupo'])) { $numero = 0; }
                $numero++;
            @endphp
            @if(!empty($funcion['grupo']))
                <p class="ventajas-acordeon__grupo">{{ $funcion['grupo'] }}</p>
            @endif
            <details class="ventajas-acordeon__item">
                <summary class="ventajas-acordeon__titulo">
                    <span class="ventajas-acordeon__nombre">
                        <span class="ventajas-acordeon__numero" aria-hidden="true">{{ $numero }}</span>
                        {{ $titulo }}
                    </span>
                    <i class="bi bi-chevron-down ventajas-acordeon__icono" aria-hidden="true"></i>
                </summary>
                <div class="ventajas-acordeon__contenido">
                    @if($extendida)
                        <p class="ventajas-acordeon__etiqueta">{{ $funcion['etiqueta_trata'] ?? 'De qué trata' }}</p>
                        <p>{{ $funcion['trata'] }}</p>
                        @if(!empty($funcion['trata_lista']))
                            <ul class="ventajas-acordeon__lista ventajas-acordeon__lista--trata">
                                @foreach($funcion['trata_lista'] as $elemento)
                                    <li>{{ $elemento }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="ventajas-acordeon__ventaja">
                            <strong><i class="bi bi-check2-circle mr-1" aria-hidden="true"></i>{{ $funcion['etiqueta_ventajas'] ?? 'Ventajas' }}</strong>
                            <ul class="ventajas-acordeon__lista">
                                @foreach($funcion['ventajas'] as $ventaja)
                                    <li>{{ $ventaja }}</li>
                                @endforeach
                            </ul>
                        </div>

                        @if(!empty($funcion['necesidad']))
                            <p class="ventajas-acordeon__etiqueta">Necesidad que resuelve</p>
                            <p>{{ $funcion['necesidad'] }}</p>
                        @endif

                        {{-- Opcional: a quién conviene ofrecer la función --}}
                        @if(!empty($funcion['a_quien']))
                            <p class="ventajas-acordeon__etiqueta">A quién ofrecérselo</p>
                            <ul class="ventajas-acordeon__publicos">
                                @foreach($funcion['a_quien'] as $publico => $texto)
                                    <li>
                                        <span class="ventajas-acordeon__publico">{{ $publico }}</span>
                                        <p class="mb-0">{{ $texto }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- "partir" y "persuadir" pueden ser un texto o una lista por público (Al centro, A la asistente…) --}}
                        <p class="ventajas-acordeon__etiqueta">Cómo partir</p>
                        @if(is_array($funcion['partir']))
                            <ul class="ventajas-acordeon__publicos">
                                @foreach($funcion['partir'] as $publico => $frase)
                                    <li>
                                        <span class="ventajas-acordeon__publico">{{ $publico }}</span>
                                        <blockquote class="ventajas-acordeon__cita">“{{ $frase }}”</blockquote>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <blockquote class="ventajas-acordeon__cita">“{{ $funcion['partir'] }}”</blockquote>
                        @endif

                        <p class="ventajas-acordeon__etiqueta">Cómo persuadir</p>
                        @if(is_array($funcion['persuadir']))
                            <ul class="ventajas-acordeon__publicos mb-0">
                                @foreach($funcion['persuadir'] as $publico => $texto)
                                    <li>
                                        <span class="ventajas-acordeon__publico">{{ $publico }}</span>
                                        <p class="mb-0">{{ $texto }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mb-0">{{ $funcion['persuadir'] }}</p>
                        @endif

                        {{-- Advertencia opcional para el vendedor --}}
                        @if(!empty($funcion['importante']))
                            <div class="ventajas-acordeon__importante">
                                <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
                                <p class="mb-0"><strong>Importante:</strong> {{ $funcion['importante'] }}</p>
                            </div>
                        @endif
                    @else
                        <p>{{ $funcion[1] }}</p>
                        <div class="ventajas-acordeon__ventaja">
                            <strong><i class="bi bi-check2-circle mr-1" aria-hidden="true"></i>Ventaja para el cliente</strong>
                            <p class="mb-0">{{ $funcion[2] }}</p>
                        </div>
                    @endif
                </div>
            </details>
        @endforeach
    </div>
</section>
