@extends('maestra')
@section('titulo', 'Videos para vendedores')
@section('contenido')
    @php
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

    <h1 class="h3 mb-3">Videos para vendedores</h1>
    @include('partials.pestanas_capacitacion', ['rutaPestanas' => 'vender.videos'])
    <a class="btn btn-outline-primary mb-3" href="#practica-fmu">Ir a la práctica de venta</a>
    <div class="row">
        @forelse($videos as $video)
            <div class="col-12 col-xl-6 mb-4">
                <section class="card h-100" aria-labelledby="titulo-video-{{ $video->id }}">
                    <div class="card-body d-flex flex-column">
                        <h2 id="titulo-video-{{ $video->id }}" class="h5">{{ $video->titulo }}</h2>
                        @if($video->descripcion)
                            <p class="text-muted">{{ $video->descripcion }}</p>
                        @endif
                        <div class="mt-auto">@include('administracion.videos.reproductor')</div>
                        @if($explicacion = $video->explicacion())
                            @include('vender.explicacion_video')
                        @endif
                        @if($demostracion = $video->demostracion())
                            @include('vender.demostracion_video')
                        @endif
                    </div>
                </section>
            </div>
        @empty
            <div class="col-12"><p class="text-muted">Sin videos de capacitación disponibles.</p></div>
        @endforelse
    </div>

    <section class="card card-body" id="practica-fmu" aria-labelledby="titulo-practica-fmu">
        <h2 class="h5" id="titulo-practica-fmu">Práctica de venta · Caso ficticio</h2>
        @if($linea === 'veterchile')
            <p>Sofía cuida de su perro Bruno y guarda sus exámenes y recetas en distintos lugares. Quiere organizar sus documentos y reservar el próximo control veterinario.</p>
        @else
            <p>Camila tiene un próximo control y guarda sus recetas y documentos en distintos lugares. Quiere saber para qué le serviría suscribirse y si podrá usar la ficha en sus próximos controles.</p>
        @endif
        <form id="prueba-fmu">
            @foreach($practica as $numero => $paso)
                <fieldset class="border rounded p-3 mb-3">
                    <legend class="h6">{{ $paso['pregunta'] }}</legend>
                    @foreach($paso['opciones'] as $indice => [$texto, $comentario, $correcta])
                        <div class="form-check mb-2">
                            <input type="radio" class="form-check-input" name="respuesta-{{ $numero }}"
                                   id="respuesta-{{ $numero }}-{{ $indice }}" required
                                   data-correcta="{{ $correcta ? 'si' : 'no' }}" data-comentario="{{ $comentario }}">
                            <label class="form-check-label" for="respuesta-{{ $numero }}-{{ $indice }}">{{ $texto }}</label>
                        </div>
                    @endforeach
                    <p class="small mb-0" data-comentario-paso hidden></p>
                </fieldset>
            @endforeach
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-catalogo-accion" type="submit">Revisar respuestas</button>
                <button class="btn btn-outline-secondary" type="reset">Reiniciar práctica</button>
            </div>
            <p class="mt-3 mb-0 fw-semibold" id="resultado-fmu" role="status"></p>
        </form>
    </section>
@endsection

@push('scripts')
<script src="{{ asset('js/demostraciones-videos.js') }}" defer></script>
<script>
    (() => {
        const formulario = document.getElementById('prueba-fmu');
        const resultado = document.getElementById('resultado-fmu');
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
    })();
</script>
@endpush
