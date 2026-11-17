@php
    $esFiltroNivel = $esFiltroNivel ?? false;
    $seleccionNivel = $esFiltroNivel ? request()->query('nivel') : old('nivel', $nivelActual ?? null);
    $descripcionesNivel = [
        'basico' => 'Para quienes necesitan las funciones esenciales del software.',
        'gold' => 'Para quienes necesitan más funciones o capacidad que en el nivel Básico.',
        'premium' => 'Para quienes necesitan el conjunto más amplio de funciones y capacidad.',
        'otro' => 'Para un plan personalizado. El detalle lo define quien configura el plan.',
    ];
    $descripcionNivel = is_string($seleccionNivel) ? ($descripcionesNivel[$seleccionNivel] ?? '') : '';
@endphp
<div class="form-group mb-3">
    <label for="nivel">Nivel del plan</label>
    <select class="form-select @error('nivel') is-invalid @enderror" id="nivel" name="nivel" aria-describedby="descripcion-nivel" data-no-select2>
        <option value="">{{ $esFiltroNivel ? 'Todos los niveles' : 'Seleccionar nivel' }}</option>
        @foreach(\App\Plan::NIVELES as $valor => $etiqueta)
            <option value="{{ $valor }}" data-descripcion="{{ $descripcionesNivel[$valor] }}" @selected($seleccionNivel === $valor)>{{ $etiqueta }}</option>
        @endforeach
    </select>
    <p class="form-text text-muted mb-0" id="descripcion-nivel" aria-live="polite">{{ $descripcionNivel }}</p>
    @error('nivel')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<script>
    document.getElementById('nivel').addEventListener('change', (event) => {
        const opcion = event.target.selectedOptions[0];
        document.getElementById('descripcion-nivel').textContent = opcion?.dataset.descripcion ?? '';
    });
</script>
