<section class="demo-practica" data-demostracion-video aria-labelledby="demo-titulo-{{ $video->id }}">
    <h3 class="h6 mb-1" id="demo-titulo-{{ $video->id }}">Demostración práctica</h3>
    <p class="demo-practica__nota">Simulación con datos ficticios.</p>
    <p class="demo-practica__caso">{{ $demostracion['caso'] }}</p>

    {{-- Paso 1: elegir una acción --}}
    <div class="demo-paso">
        <p class="demo-paso__titulo" id="demo-paso1-{{ $video->id }}">
            <span class="demo-paso__numero" aria-hidden="true">1</span> Elige una acción del sistema
        </p>
        <div class="demo-practica__acciones" role="group" aria-labelledby="demo-paso1-{{ $video->id }}">
            @foreach($demostracion['pasos'] as $indice => [$accion, $titulo, $detalle, $ventaja])
                <button class="btn btn-outline-primary btn-sm" type="button" data-demo-paso
                        aria-pressed="false" aria-controls="demo-panel-{{ $video->id }}"
                        data-titulo="{{ $titulo }}" data-detalle="{{ $detalle }}" data-ventaja="{{ $ventaja }}">{{ $accion }}</button>
            @endforeach
        </div>

        <div class="demo-practica__panel" id="demo-panel-{{ $video->id }}" data-demo-panel data-estado="vacio" aria-live="polite">
            <i class="bi bi-hand-index demo-practica__panel-icono" aria-hidden="true" data-demo-icono></i>
            <div>
                <strong data-demo-titulo data-inicial="Presiona una de las acciones de arriba">Presiona una de las acciones de arriba</strong>
                <p class="demo-practica__detalle" data-demo-detalle data-inicial="Verás qué hace esa función en el sistema y qué ventaja tiene para el cliente.">Verás qué hace esa función en el sistema y qué ventaja tiene para el cliente.</p>
                <div class="demo-practica__beneficio" data-demo-beneficio hidden>
                    <strong>Ventaja para el cliente</strong>
                    <p class="mb-0" data-demo-ventaja></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Paso 2: escribir el argumento --}}
    <div class="demo-paso">
        <label class="demo-paso__titulo" for="demo-propuesta-{{ $video->id }}">
            <span class="demo-paso__numero" aria-hidden="true">2</span> Escribe cómo explicarías esa ventaja
        </label>
        <textarea class="form-control" rows="3" id="demo-propuesta-{{ $video->id }}" data-demo-propuesta
                  placeholder="Con esta función podrás…" maxlength="700"></textarea>
    </div>

    {{-- Paso 3: comparar con el ejemplo --}}
    <div class="demo-paso mb-0">
        <p class="demo-paso__titulo">
            <span class="demo-paso__numero" aria-hidden="true">3</span> Compara con un ejemplo
        </p>
        <div class="demo-practica__botones">
            <button class="btn btn-catalogo-accion btn-sm" type="button" data-demo-ejemplo disabled
                    aria-describedby="demo-ayuda-{{ $video->id }}">Ver ejemplo de argumento</button>
            <button class="btn btn-outline-secondary btn-sm" type="button" data-demo-reiniciar>Reiniciar</button>
        </div>
        <p class="demo-practica__ayuda" id="demo-ayuda-{{ $video->id }}" data-demo-ayuda>Primero elige una acción en el paso 1.</p>
        <div class="demo-practica__argumento" data-demo-argumento-caja hidden>
            <strong>Ejemplo de argumento</strong>
            <p class="mb-0" data-demo-argumento aria-live="polite"></p>
        </div>
    </div>
</section>
