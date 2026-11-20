{{-- Práctica de venta (caso ficticio). Se usa en la pill "Práctica de venta" de cada video.
     Variables: $practica, $linea, $sufijo (para que los id no se repitan entre videos). --}}
<section class="mt-3 border rounded p-3" aria-labelledby="titulo-practica-{{ $sufijo }}">
    <h3 class="h6" id="titulo-practica-{{ $sufijo }}">Práctica de venta · Caso ficticio</h3>
    @if($linea === 'veterchile')
        <p>Sofía cuida de su perro Bruno y guarda sus exámenes y recetas en distintos lugares. Quiere organizar sus documentos y reservar el próximo control veterinario.</p>
    @else
        <p>Camila tiene un próximo control y guarda sus recetas y documentos en distintos lugares. Quiere saber para qué le serviría suscribirse y si podrá usar la ficha en sus próximos controles.</p>
    @endif
    <form data-practica-venta>
        @foreach($practica as $numero => $paso)
            <fieldset class="border rounded p-3 mb-3">
                <legend class="h6">{{ $paso['pregunta'] }}</legend>
                @foreach($paso['opciones'] as $indice => [$texto, $comentario, $correcta])
                    <div class="form-check mb-2">
                        <input type="radio" class="form-check-input" name="respuesta-{{ $sufijo }}-{{ $numero }}"
                               id="respuesta-{{ $sufijo }}-{{ $numero }}-{{ $indice }}" required
                               data-correcta="{{ $correcta ? 'si' : 'no' }}" data-comentario="{{ $comentario }}">
                        <label class="form-check-label" for="respuesta-{{ $sufijo }}-{{ $numero }}-{{ $indice }}">{{ $texto }}</label>
                    </div>
                @endforeach
                <p class="small mb-0" data-comentario-paso hidden></p>
            </fieldset>
        @endforeach
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-catalogo-accion" type="submit">Revisar respuestas</button>
            <button class="btn btn-outline-secondary" type="reset">Reiniciar práctica</button>
        </div>
        <p class="mt-3 mb-0 fw-semibold" role="status" data-resultado-practica></p>
    </form>
</section>
