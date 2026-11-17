<form method="post" action="{{ route('seguimientos.update', $registro) }}" class="d-flex flex-wrap align-items-start gap-2">
    @csrf
    @method('patch')
    <select name="estado" class="form-select w-auto" aria-label="Estado del seguimiento" data-no-select2>
        @foreach(\App\SeguimientoContacto::ESTADOS as $valor => $etiqueta)
            <option value="{{ $valor }}" @selected($registro->estado === $valor)>{{ $etiqueta }}</option>
        @endforeach
    </select>
    <input type="text" name="nota" class="form-control w-auto" aria-label="Nota de seguimiento" maxlength="1000"
           placeholder="Nota de respuesta" value="{{ $registro->nota }}">
    <button class="btn btn-outline-primary">Guardar</button>
    @if($registro->respondio_en)
        <small class="text-muted w-100">Respondió el {{ $registro->respondio_en->format('d-m-Y H:i') }}</small>
    @endif
</form>
