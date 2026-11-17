<div class="form-group">
    <label for="precio_promocional">Precio promocional</label>
    <input type="number" min="0" step="0.01" name="precio_promocional" id="precio_promocional"
           class="form-control @error('precio_promocional') is-invalid @enderror"
           value="{{ old('precio_promocional', $precioPromocional ?? '') }}">
    <small class="form-text text-muted">Déjalo vacío para aplicar el precio regular.</small>
    @error('precio_promocional')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
