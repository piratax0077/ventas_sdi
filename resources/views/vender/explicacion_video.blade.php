<section class="mt-3 border rounded p-3" aria-labelledby="explicacion-{{ $video->id }}">
    <h3 class="h6" id="explicacion-{{ $video->id }}">{{ $explicacion['titulo'] }}</h3>
    <p class="small">{{ $explicacion['introduccion'] }}</p>
    <p class="small text-muted">Selecciona una función para revisar cómo explicarla y qué ventaja aporta.</p>
    {{-- Acordeón (estilos en "VIDEOS PARA VENDEDORES · ACORDEÓN DE VENTAJAS" de main.css) --}}
    <div class="ventajas-acordeon">
        @foreach($explicacion['funciones'] as [$titulo, $descripcion, $ventaja])
            <details class="ventajas-acordeon__item">
                <summary class="ventajas-acordeon__titulo">
                    <span>{{ $titulo }}</span>
                    <i class="bi bi-chevron-down ventajas-acordeon__icono" aria-hidden="true"></i>
                </summary>
                <div class="ventajas-acordeon__contenido">
                    <p>{{ $descripcion }}</p>
                    <div class="ventajas-acordeon__ventaja">
                        <strong><i class="bi bi-check2-circle mr-1" aria-hidden="true"></i>Ventaja para el cliente</strong>
                        <p class="mb-0">{{ $ventaja }}</p>
                    </div>
                </div>
            </details>
        @endforeach
    </div>
</section>
