<section class="border rounded p-3 mt-3" data-demostracion-video aria-labelledby="demo-titulo-{{ $video->id }}">
    <h3 class="h6" id="demo-titulo-{{ $video->id }}">Demostración práctica</h3>
    <p class="small text-muted">Simulación con datos ficticios.</p>
    <p>{{ $demostracion['caso'] }}</p>
    <div class="d-flex flex-wrap gap-2 mb-3" aria-label="Acciones de la demostración">
        @foreach($demostracion['pasos'] as $indice => [$accion, $titulo, $detalle, $ventaja])
            <button class="btn btn-outline-primary btn-sm" type="button" data-demo-paso
                    aria-pressed="false" aria-controls="demo-panel-{{ $video->id }}"
                    data-titulo="{{ $titulo }}" data-detalle="{{ $detalle }}" data-ventaja="{{ $ventaja }}">{{ $accion }}</button>
        @endforeach
    </div>
    <div class="bg-light border rounded p-3 mb-3" id="demo-panel-{{ $video->id }}" aria-live="polite">
        <strong data-demo-titulo>Selecciona una acción para practicar</strong>
        <p class="small mt-2 mb-0" data-demo-detalle></p>
        <div data-demo-beneficio hidden class="mt-3 border-top pt-3">
            <strong>Ventaja para el cliente</strong>
            <p class="mb-0" data-demo-ventaja></p>
        </div>
    </div>
    <label class="small fw-semibold" for="demo-propuesta-{{ $video->id }}">Practica cómo explicarías esa ventaja</label>
    <textarea class="form-control mb-2" rows="2" id="demo-propuesta-{{ $video->id }}" data-demo-propuesta
              placeholder="Con esta función podrás…" maxlength="700"></textarea>
    <div class="d-flex flex-wrap gap-2">
        <button class="btn btn-catalogo-accion btn-sm" type="button" data-demo-ejemplo>Ver ejemplo de argumento</button>
        <button class="btn btn-outline-secondary btn-sm" type="button" data-demo-reiniciar>Reiniciar</button>
    </div>
    <p class="small mt-2 mb-0" data-demo-argumento aria-live="polite"></p>
</section>
