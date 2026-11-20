@extends('maestra')
@section('titulo', 'Campañas')
@section('contenido')
    <h1 class="h3">Preparar campaña</h1>
    @include('notificacion')
    <form method="get" action="{{ route('campanas.index') }}" class="d-flex flex-wrap align-items-end gap-2 mb-4">
        <div>
            <label for="canal-filtro">Canal</label>
            <select id="canal-filtro" name="canal" class="form-select" data-no-select2>
                <option value="correo" @selected($canal === 'correo')>Correo electrónico</option>
                <option value="whatsapp" @selected($canal === 'whatsapp')>WhatsApp</option>
            </select>
        </div>
        <div>
            <label for="rol-filtro">Público</label>
            <select id="rol-filtro" name="rol" class="form-select" data-no-select2>
                <option value="">Todos los roles</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}" @selected($rolId === (int) $rol->id)>{{ $rol->alias ?: $rol->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="buscar">Buscar cliente</label>
            <input id="buscar" name="buscar" type="search" class="form-control" value="{{ $busqueda }}">
        </div>
        <button class="btn btn-catalogo-accion">Filtrar</button>
    </form>

    <form method="post" action="{{ route('campanas.preparar') }}">
        @csrf
        <input type="hidden" name="lote" value="{{ old('lote', $lote) }}">
        <input type="hidden" name="canal" value="{{ $canal }}">
        <div class="row">
            <div class="col-lg-5 mb-3">
                @if($canal === 'correo')
                    <label for="asunto">Asunto</label>
                    <input id="asunto" name="asunto" class="form-control mb-3" maxlength="120" required value="{{ old('asunto') }}">
                @endif
                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" class="form-control mb-3" maxlength="1000" rows="6" required>{{ old('mensaje') }}</textarea>
                <button class="btn btn-catalogo-accion" @disabled($destinatarios->isEmpty())>Preparar mensajes</button>
            </div>
            <div class="col-lg-7">
                <h2 class="h5">Destinatarios disponibles ({{ $destinatarios->total() }})</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="marcar-pagina" aria-label="Seleccionar todos los destinatarios de esta página"></th>
                            <th>Cliente</th>
                            <th>{{ $canal === 'correo' ? 'Correo' : 'Móvil' }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($destinatarios as $fila)
                            @php($cliente = $fila['cliente'])
                            <tr>
                                <td><input type="checkbox" name="destinatarios[]" value="{{ $cliente->id }}" id="destinatario-{{ $cliente->id }}" @checked(in_array($cliente->id, (array) old('destinatarios', [])))></td>
                                <td><label for="destinatario-{{ $cliente->id }}">{{ $cliente->nombre }} {{ $cliente->primer_apellido }} {{ $cliente->segundo_apellido }}</label></td>
                                <td>{{ $fila['contacto'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3">Sin destinatarios para este filtro.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $destinatarios->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </form>
    <script>
        document.getElementById('marcar-pagina').addEventListener('change', (event) => {
            document.querySelectorAll('input[name="destinatarios[]"]').forEach((casilla) => {
                casilla.checked = event.target.checked;
            });
        });
    </script>
@endsection
