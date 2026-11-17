@php
    $esFiltro = $esFiltro ?? false;
    $campo = $esFiltro ? 'segmento' : 'segmento_catalogo';
    $seleccion = $esFiltro ? request()->query('segmento') : old($campo, $segmentoActual ?? null);
@endphp
<div class="form-group mb-3">
    <label for="{{ $campo }}">Línea y público</label>
    <select class="form-select @error($campo) is-invalid @enderror" name="{{ $campo }}" id="{{ $campo }}" data-no-select2>
        <option value="">{{ $esFiltro ? 'Todo el catálogo' : 'Sin clasificar' }}</option>
        @if($esFiltro)
            <option value="sin-clasificar" @selected($seleccion === 'sin-clasificar')>Sin clasificar</option>
        @endif
        @foreach(\App\CatalogoSegmentos::LINEAS as $linea => [$nombreLinea, $publicos])
            <optgroup label="{{ $nombreLinea }}">
                @if($esFiltro && count($publicos) > 1)
                    <option value="{{ $linea }}" @selected($seleccion === $linea)>{{ $nombreLinea }} · todos</option>
                @endif
                @foreach($publicos as $publico => $nombrePublico)
                    <option value="{{ $linea }}.{{ $publico }}" @selected($seleccion === "$linea.$publico")>
                        {{ $nombrePublico }}
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
    @error($campo)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
