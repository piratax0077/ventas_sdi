@extends("maestra")
@section("titulo", "Ver Mantenimiento")
@section("contenido")
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fa fa-wrench"></i> Detalle del Mantenimiento</h5>
                    <span class="badge bg-{{ $mantenimiento->etiqueta_estado }} fs-6">
                        {{ ucfirst($mantenimiento->estado) }}
                    </span>
                </div>
                <div class="card-body">
                    @include("notificacion")

                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted">Descripción</h6>
                            <p class="lead">{{ $mantenimiento->descripcion }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Tipo</h6>
                            <p>
                                <span class="badge bg-info">{{ ucfirst($mantenimiento->tipo) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Estado</h6>
                            <p>
                                <span class="badge bg-{{ $mantenimiento->etiqueta_estado }} p-2">
                                    {{ ucfirst($mantenimiento->estado) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Fecha Inicio</h6>
                            <p>
                                <strong>
                                    <i class="fa fa-calendar"></i>
                                    {{ $mantenimiento->fecha_inicio->format('d/m/Y H:i') }}
                                </strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Fecha Fin</h6>
                            <p>
                                @if($mantenimiento->fecha_fin)
                                    <strong>
                                        <i class="fa fa-calendar-check"></i>
                                        {{ $mantenimiento->fecha_fin->format('d/m/Y H:i') }}
                                    </strong>
                                @else
                                    <em class="text-muted">Aún no finalizado</em>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Responsable</h6>
                            <p>
                                @if($mantenimiento->responsable)
                                    <strong><i class="fa fa-user"></i> {{ $mantenimiento->responsable }}</strong>
                                @else
                                    <em class="text-muted">No asignado</em>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Creado</h6>
                            <p>
                                <small class="text-muted">{{ $mantenimiento->created_at->format('d/m/Y H:i') }}</small>
                            </p>
                        </div>
                    </div>

                    @if($mantenimiento->notas)
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6 class="text-muted">Notas</h6>
                                <div class="alert alert-light border">
                                    {{ $mantenimiento->notas }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('mantenimiento.edit', $mantenimiento->id) }}" class="btn btn-warning">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('mantenimiento.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Volver
                        </a>
                        <form action="{{ route('mantenimiento.destroy', $mantenimiento->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('¿Eliminar este mantenimiento de forma permanente?')">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
