@php($tiposSeleccionados = (array) old('tipos', $tiposActuales ?? []))
<fieldset class="mb-3">
    <legend class="h6">Tipos de software <span class="text-danger">*</span></legend>
    <p class="small text-muted mb-2">Puedes marcar varias opciones.</p>
    @foreach(\App\ProductoSoftware::TIPOS as $valor => $etiqueta)
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="tipo-{{ $valor }}" name="tipos[]" value="{{ $valor }}"
                   @checked(in_array($valor, $tiposSeleccionados, true))>
            <label class="form-check-label" for="tipo-{{ $valor }}">{{ $etiqueta }}</label>
        </div>
    @endforeach
    @error('tipos')
        <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('tipos.*')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</fieldset>
