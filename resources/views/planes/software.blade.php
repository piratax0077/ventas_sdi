<div class="form-group mb-3">
    <label for="software_ids">Software incluido</label>
    @if($software->isEmpty())
        <p class="text-muted">Crea primero el software que incluirá este plan.</p>
        <a href="{{ route('productos-software.create') }}" class="btn btn-outline-primary">Agregar software</a>
    @else
        <select multiple required class="form-control" name="software_ids[]" id="software_ids" size="{{ min(5, $software->count()) }}">
            @foreach($software as $producto)
                <option value="{{ $producto->id }}" @selected(in_array($producto->id, (array) old('software_ids', $softwareSeleccionado ?? [])))>
                    {{ $producto->nombre }} · {{ \App\CatalogoSegmentos::etiqueta($producto->segmento_catalogo) }}{{ $producto->estado ? '' : ' (Inactivo)' }}
                </option>
            @endforeach
        </select>
    @endif
    @error('software_ids')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
