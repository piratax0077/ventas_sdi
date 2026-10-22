@extends("maestra")
@section("titulo", "Editar Mantenimiento")
@section("contenido")
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fa fa-edit"></i> Editar Mantenimiento</h5>
                </div>
                <div class="card-body">
                    @include("notificacion")

                    <form action="{{ route('mantenimiento.update', $mantenimiento->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                                      name="descripcion" rows="4" placeholder="Descripción detallada del mantenimiento..."
                                      required>{{ old('descripcion', $mantenimiento->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo') is-invalid @enderror" id="tipo"
                                        name="tipo" required>
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tipo', $mantenimiento->tipo) === $tipo ? 'selected' : '' }}>
                                            {{ ucfirst($tipo) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select @error('estado') is-invalid @enderror" id="estado"
                                        name="estado" required>
                                    <option value="">Seleccione un estado</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado }}" {{ old('estado', $mantenimiento->estado) === $estado ? 'selected' : '' }}>
                                            {{ ucfirst($estado) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                       id="fecha_inicio" name="fecha_inicio" required
                                       value="{{ old('fecha_inicio', $mantenimiento->fecha_inicio->format('Y-m-d\TH:i')) }}">
                                @error('fecha_inicio')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="datetime-local" class="form-control @error('fecha_fin') is-invalid @enderror"
                                       id="fecha_fin" name="fecha_fin"
                                       value="{{ old('fecha_fin', $mantenimiento->fecha_fin ? $mantenimiento->fecha_fin->format('Y-m-d\TH:i') : '') }}">
                                @error('fecha_fin')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="responsable" class="form-label">Responsable</label>
                            <input type="text" class="form-control @error('responsable') is-invalid @enderror"
                                   id="responsable" name="responsable" placeholder="Nombre del responsable"
                                   value="{{ old('responsable', $mantenimiento->responsable) }}">
                            @error('responsable')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas</label>
                            <textarea class="form-control @error('notas') is-invalid @enderror" id="notas"
                                      name="notas" rows="3" placeholder="Notas adicionales...">{{ old('notas', $mantenimiento->notas) }}</textarea>
                            @error('notas')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fa fa-save"></i> Actualizar
                            </button>
                            <a href="{{ route('mantenimiento.show', $mantenimiento->id) }}" class="btn btn-secondary">
                                <i class="fa fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
