<section class="mt-3 border rounded p-3" aria-labelledby="explicacion-{{ $video->id }}">
    <h3 class="h6" id="explicacion-{{ $video->id }}">{{ $explicacion['titulo'] }}</h3>
    <p class="small">{{ $explicacion['introduccion'] }}</p>
    <p class="small text-muted">Selecciona una función para revisar cómo explicarla y qué ventaja aporta.</p>
    @foreach($explicacion['funciones'] as [$titulo, $descripcion, $ventaja])
        <details class="border-top py-2">
            <summary class="fw-semibold text-primary">{{ $titulo }}</summary>
            <p class="small mt-2">{{ $descripcion }}</p>
            <div class="bg-light rounded p-2">
                <strong class="small">Ventaja para el cliente</strong>
                <p class="small mb-0">{{ $ventaja }}</p>
            </div>
        </details>
    @endforeach
</section>
