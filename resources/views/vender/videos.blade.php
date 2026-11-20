@extends('maestra')
@section('titulo', 'Videos para vendedores')
{{-- Esta vista usa todo el ancho disponible --}}
@section('clase_contenedor', 'container-fluid')
@section('contenido')
    @php
        // En el menú y el desplegable se omite la línea en los títulos "Veterchile para …" / "Medichile para …"
        $tituloMenu = function ($titulo) {
            return preg_match('/^(Veterchile|Medichile)\s+para\s/iu', $titulo)
                ? ucfirst(preg_replace('/^(Veterchile|Medichile)\s+/iu', '', $titulo))
                : $titulo;
        };
        $practica = $linea === 'veterchile' ? [
            [
                'pregunta' => '¿Qué le mostrarías primero a una tutora que tiene los exámenes de su mascota repartidos?',
                'opciones' => [
                    ['Cómo consultar los documentos disponibles en la Ficha Veterinaria Única.', 'Parte por su necesidad y muestra un ejemplo con datos ficticios.', true],
                    ['Le asegurarías que todos sus exámenes históricos ya están cargados.', 'Confirma qué documentos están disponibles en su cuenta.', false],
                ],
            ],
            [
                'pregunta' => '¿Cómo demostrarías la reserva de una hora?',
                'opciones' => [
                    ['Confirmarías una hora inmediata sin revisar el calendario.', 'La reserva depende de las horas y servicios disponibles.', false],
                    ['Buscarías una atención, revisarías la disponibilidad y mostrarías cómo reservar.', 'Así explicas el proceso sin prometer una hora determinada.', true],
                ],
            ],
            [
                'pregunta' => '¿Qué debes confirmar antes de ofrecer una suscripción?',
                'opciones' => [
                    ['Las prestaciones incluidas, el precio y el ciclo de cobro.', 'Así la tutora puede evaluar el plan con información concreta.', true],
                    ['Que todos los planes incluyen las mismas funciones.', 'Las prestaciones pueden variar. Consulta el catálogo vigente.', false],
                ],
            ],
        ] : [
            [
                'pregunta' => '¿Cómo iniciarías la conversación?',
                'opciones' => [
                    ['Le explicarías todos los módulos antes de preguntarle qué necesita.', 'Primero averigua qué le cuesta encontrar. Después elige el beneficio que responde a esa necesidad.', false],
                    ['Le preguntarías cómo encuentra sus recetas y documentos antes de un control.', 'Así puedes relacionar la FMU con una dificultad concreta del paciente.', true],
                    ['Le pedirías que contrate el plan anual para poder explicárselo.', 'Primero muestra el producto y explica las alternativas de contratación.', false],
                ],
            ],
            [
                'pregunta' => '¿Qué ventaja le mostrarías en la demostración?',
                'opciones' => [
                    ['Que la FMU garantiza tener todos los documentos de todas sus consultas.', 'Explica que puede consultar los documentos que tenga disponibles en la ficha.', false],
                    ['Que la FMU decide qué controles necesita.', 'La demostración debe centrarse en consultar documentos, recetas y controles disponibles.', false],
                    ['Cómo consultar los documentos y recetas disponibles desde un solo lugar.', 'Es una ventaja concreta que puedes mostrar con datos ficticios.', true],
                ],
            ],
            [
                'pregunta' => '¿Cómo explicarías el valor de mantener la suscripción?',
                'opciones' => [
                    ['Podrá volver a consultar sus antecedentes cuando los necesite. Revisarías con ella el ciclo y el precio vigente.', 'Relaciona la continuidad con un uso recurrente y confirma las condiciones de la suscripción.', true],
                    ['Le asegurarías que el precio se mantiene para siempre.', 'Confirma el precio vigente y evita prometer condiciones futuras.', false],
                    ['Le ofrecerías renovar sin explicarle cuándo se cobra.', 'Aclara si el cobro es mensual o anual y las condiciones aplicables.', false],
                ],
            ],
            [
                'pregunta' => 'Camila quiere usar la FMU desde su celular. ¿Qué le mostrarías?',
                'opciones' => [
                    ['Le dirías que todos los planes incluyen la app.', 'Revisa primero si la app está incluida en el plan que le vas a ofrecer.', false],
                    ['Verificarías qué plan incluye la app y le mostrarías cómo consultar su FMU, pedir horas y ver exámenes disponibles.', 'Así puede comparar los planes y ver cómo le serviría la app en una situación concreta.', true],
                    ['Le prometerías una hora inmediata y acceso a todos sus exámenes.', 'Las horas dependen de la disponibilidad y la app muestra los exámenes disponibles en su cuenta.', false],
                ],
            ],
            [
                'pregunta' => 'Al final, Camila pregunta por controles de su hijo. ¿Qué haces?',
                'opciones' => [
                    ['Le confirmarías que todos los planes incluyen Control de Niño Sano.', 'Revisa las prestaciones del plan y confirma si ese módulo está disponible.', false],
                    ['Revisarías el plan con Control de Niño Sano y le ofrecerías una demostración breve.', 'Muestras la opción pertinente y propones un siguiente paso concreto.', true],
                    ['Le recomendarías el plan con más módulos sin revisar cuáles le sirven.', 'Parte por la necesidad que te contó y compara las opciones que la cubren.', false],
                ],
            ],
        ];
    @endphp

    {{-- Tipografía compacta de la sección (ver "VIDEOS PARA VENDEDORES · TIPOGRAFÍA" en main.css) --}}
    <div class="seccion-videos">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0">Videos para vendedores</h1>
        <a class="btn btn-outline-primary" href="#practica-fmu" data-ir-practica>
            <i class="bi bi-clipboard-check mr-1" aria-hidden="true"></i> Ir a la práctica de venta
        </a>
    </div>
    @include('partials.pestanas_capacitacion', ['rutaPestanas' => 'vender.videos'])
    @if($videos->isEmpty())
        @include('partials.estado_vacio', [
            'titulo' => 'Aún no hay videos de capacitación',
            'mensaje' => 'Cuando se publiquen videos para esta línea, aparecerán aquí.',
        ])
    @else
        {{-- Lista de videos a la izquierda y un solo video visible a la derecha (ver public/js/videos-lista.js) --}}
        <div class="videos-lista mb-4" data-videos-lista>
            <div class="videos-lista__indice">
                <label class="videos-lista__selector-label" for="selector-video">Video</label>
                {{-- El primer video es la presentación general; el resto se agrupa bajo "Soluciones" --}}
                <select class="form-select videos-lista__selector" id="selector-video" data-no-select2 data-videos-selector>
                    <option value="{{ $videos->first()->id }}">{{ $videos->first()->titulo }}</option>
                    @if($videos->count() > 1)
                        <optgroup label="Soluciones">
                            @foreach($videos->skip(1) as $video)
                                <option value="{{ $video->id }}">{{ $tituloMenu($video->titulo) }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                <nav class="videos-lista__menu" aria-label="Videos de capacitación">
                    @foreach($videos as $video)
                        @if($loop->index === 1)
                            <p class="videos-lista__grupo">Soluciones</p>
                        @endif
                        <button type="button" class="videos-lista__item" data-videos-boton="{{ $video->id }}"
                                aria-controls="video-{{ $video->id }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $tituloMenu($video->titulo) }}
                        </button>
                    @endforeach
                </nav>
            </div>
            <div class="videos-lista__panel">
                @foreach($videos as $video)
                    <section class="card video-card" id="video-{{ $video->id }}" data-videos-panel="{{ $video->id }}"
                             aria-labelledby="titulo-video-{{ $video->id }}" @unless($loop->first) hidden @endunless>
                        <div class="card-body d-flex flex-column">
                            <h2 id="titulo-video-{{ $video->id }}" class="h5 video-card-titulo">{{ $video->titulo }}</h2>
                            @if($video->descripcion)
                                <p class="video-card-descripcion">{{ $video->descripcion }}</p>
                            @endif
                            <div>@include('administracion.videos.reproductor')</div>
                            @php
                                $explicacion = $video->explicacion();
                                $demostracion = $video->demostracion();
                            @endphp
                            {{-- Ventajas, Demostración y Práctica de venta en pestañas tipo pill (Bootstrap 5 tab) --}}
                                <div class="video-pestanas">
                                    <div class="nav nav-pills video-pestanas__nav" role="tablist" aria-label="Contenido del video">
                                        @if($explicacion)
                                            <button type="button" class="nav-link active" role="tab"
                                                    id="tab-ventajas-{{ $video->id }}" data-bs-toggle="pill"
                                                    data-bs-target="#pane-ventajas-{{ $video->id }}"
                                                    aria-controls="pane-ventajas-{{ $video->id }}" aria-selected="true">
                                                <i class="bi bi-star mr-1" aria-hidden="true"></i> Ventajas
                                            </button>
                                        @endif
                                        @if($demostracion)
                                            <button type="button" class="nav-link {{ $explicacion ? '' : 'active' }}" role="tab"
                                                    id="tab-demo-{{ $video->id }}" data-bs-toggle="pill"
                                                    data-bs-target="#pane-demo-{{ $video->id }}"
                                                    aria-controls="pane-demo-{{ $video->id }}" aria-selected="{{ $explicacion ? 'false' : 'true' }}">
                                                <i class="bi bi-play-circle mr-1" aria-hidden="true"></i> Demostración
                                            </button>
                                        @endif
                                        <button type="button" class="nav-link {{ $explicacion || $demostracion ? '' : 'active' }}" role="tab"
                                                id="tab-practica-{{ $video->id }}" data-bs-toggle="pill" data-pestana-practica
                                                data-bs-target="#pane-practica-{{ $video->id }}"
                                                aria-controls="pane-practica-{{ $video->id }}" aria-selected="{{ $explicacion || $demostracion ? 'false' : 'true' }}">
                                            <i class="bi bi-clipboard-check mr-1" aria-hidden="true"></i> Práctica de venta
                                        </button>
                                    </div>
                                    <div class="tab-content">
                                        @if($explicacion)
                                            <div class="tab-pane fade show active" id="pane-ventajas-{{ $video->id }}"
                                                 role="tabpanel" aria-labelledby="tab-ventajas-{{ $video->id }}" tabindex="0">
                                                @include('vender.explicacion_video')
                                            </div>
                                        @endif
                                        @if($demostracion)
                                            <div class="tab-pane fade {{ $explicacion ? '' : 'show active' }}" id="pane-demo-{{ $video->id }}"
                                                 role="tabpanel" aria-labelledby="tab-demo-{{ $video->id }}" tabindex="0">
                                                @include('vender.demostracion_video')
                                            </div>
                                        @endif
                                        <div class="tab-pane fade {{ $explicacion || $demostracion ? '' : 'show active' }}" id="pane-practica-{{ $video->id }}"
                                             role="tabpanel" aria-labelledby="tab-practica-{{ $video->id }}" tabindex="0">
                                            @include('vender.practica_venta', ['sufijo' => $video->id])
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </section>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Sin videos, la práctica se muestra sola debajo del estado vacío --}}
    @if($videos->isEmpty())
        <div id="practica-fmu">
            @include('vender.practica_venta', ['sufijo' => 'general'])
        </div>
    @endif
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/demostraciones-videos.js') }}" defer></script>
<script src="{{ asset('js/videos-lista.js') }}?v={{ filemtime(public_path('js/videos-lista.js')) }}" defer></script>
<script>
    (() => {
        // "Ir a la práctica de venta": abre la pill de práctica del video visible
        const irPractica = document.querySelector('[data-ir-practica]');
        irPractica?.addEventListener('click', (evento) => {
            const panel = document.querySelector('[data-videos-panel]:not([hidden])');
            const pestana = panel?.querySelector('[data-pestana-practica]');
            if (!pestana || !window.bootstrap) return;
            evento.preventDefault();
            bootstrap.Tab.getOrCreateInstance(pestana).show();
            pestana.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        // Una práctica por video: cada formulario revisa sus propias respuestas
        document.querySelectorAll('[data-practica-venta]').forEach((formulario) => {
        const resultado = formulario.querySelector('[data-resultado-practica]');
        const pasos = Array.from(formulario.querySelectorAll('fieldset'));

        formulario.addEventListener('submit', (evento) => {
            evento.preventDefault();
            let correctas = 0;
            for (const paso of pasos) {
                const elegida = paso.querySelector('input:checked');
                const comentario = paso.querySelector('[data-comentario-paso]');
                const correcta = elegida.dataset.correcta === 'si';
                if (correcta) correctas++;
                comentario.textContent = (correcta ? 'Correcto. ' : 'Para mejorar: ') + elegida.dataset.comentario;
                comentario.className = correcta ? 'small mb-0 text-success' : 'small mb-0 text-danger';
                comentario.hidden = false;
            }
            resultado.textContent = correctas + ' de ' + pasos.length + ' respuestas correctas.';
        });

        formulario.addEventListener('reset', () => {
            resultado.textContent = '';
            for (const paso of pasos) {
                const comentario = paso.querySelector('[data-comentario-paso]');
                comentario.textContent = '';
                comentario.hidden = true;
            }
        });
        });
    })();
</script>
@endpush
