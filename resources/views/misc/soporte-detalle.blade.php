@extends("maestra")
@section("titulo", "Ticket " . $solicitud->numero_ticket)
@section("contenido")

@php
    $badgeEstado = [
        'abierta'     => 'bg-warning text-dark',
        'en_progreso' => 'bg-info text-dark',
        'resuelta'    => 'bg-success',
        'cerrada'     => 'bg-secondary',
    ];
    $iconEstado = [
        'abierta'     => 'fa-envelope-open',
        'en_progreso' => 'fa-spinner fa-spin',
        'resuelta'    => 'fa-check-circle',
        'cerrada'     => 'fa-lock',
    ];
    $badgePrioridad = [
        'baja'    => 'bg-secondary',
        'media'   => 'bg-primary',
        'alta'    => 'bg-warning text-dark',
        'urgente' => 'bg-danger',
    ];
    $labelTipo = [
        'problema_tecnico'  => 'Problema Técnico',
        'consulta_general'  => 'Consulta General',
        'reporte_error'     => 'Reporte de Error',
        'solicitud_feature' => 'Solicitud de Mejora',
        'acceso_cuenta'     => 'Problema de Acceso',
        'facturacion'       => 'Facturación',
        'otros'             => 'Otros',
    ];
    $iconTipo = [
        'problema_tecnico'  => 'fa-bug',
        'consulta_general'  => 'fa-question-circle',
        'reporte_error'     => 'fa-exclamation-triangle',
        'solicitud_feature' => 'fa-lightbulb',
        'acceso_cuenta'     => 'fa-lock',
        'facturacion'       => 'fa-receipt',
        'otros'             => 'fa-ellipsis-h',
    ];
    $esAdmin = Auth::check() && Auth::user()->hasAnyRole(['admin', 'Admin']);
@endphp

<div class="row">
    <div class="col-12">

        {{-- Header --}}
        <div class=" border-0 shadow-sm mb-4 gradient-header">
            <div class=" py-4 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="text-dark">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <a href="{{ route('soporte.mis-solicitudes') }}" class="btn btn-sm btn-outline-secondary">
                           Mis Solicitudes
                        </a>
                        <span class="text-muted">›</span>
                        <code class="text-primary fs-6">{{ $solicitud->numero_ticket }}</code>
                    </div>
                    <h3 class="mb-1 fw-bold text-truncate" style="max-width: 600px;">
                        <i class="fa fa-ticket-alt me-2"></i>{{ $solicitud->asunto }}
                    </h3>
                    <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                        <span class="badge {{ $badgeEstado[$solicitud->estado] ?? 'bg-secondary' }} px-3 py-2">
                            <i class="fa {{ $iconEstado[$solicitud->estado] ?? 'fa-circle' }} me-1"></i>
                            {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                        </span>
                        <span class="badge {{ $badgePrioridad[$solicitud->prioridad] ?? 'bg-secondary' }} px-3 py-2">
                            <i class="fa fa-flag me-1"></i>
                            Prioridad {{ ucfirst($solicitud->prioridad) }}
                        </span>
                        <small class="text-muted">
                            <i class="fa fa-calendar me-1"></i>Creado el {{ $solicitud->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
                <a href="{{ route('soporte.index') }}" class="btn btn-info btn-lg">
                    <i class="fa fa-plus me-2"></i> Nueva Solicitud
                </a>
            </div>
        </div>

        @include("notificacion")

        <div class="row g-4">

            {{-- ── Columna principal ─────────────────────────────────────────── --}}
            <div class="col-lg-8">

                {{-- Descripción --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 fw-semibold">
                       Descripción del Problema
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="p-3 rounded bg-light border" style="white-space: pre-wrap; font-size: .95rem; line-height: 1.7;">{{ $solicitud->descripcion }}</div>
                    </div>
                </div>

                {{-- Archivos adjuntos --}}
                @if($solicitud->archivos->isNotEmpty())
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fa fa-paperclip me-2 text-primary"></i>Archivos Adjuntos
                            <span class="badge bg-primary ms-1">{{ $solicitud->archivos->count() }}</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($solicitud->archivos as $archivo)
                            @php
                                $esImagen = strpos($archivo->tipo_mime ?? '', 'image/') === 0;
                                $tamanoKB = $archivo->tamaño ? round($archivo->tamaño / 1024, 1) : null;
                            @endphp
                            <div class="col-sm-6 col-md-4">
                                <div class="card border h-100">
                                    @if($esImagen)
                                        <img src="{{ asset('storage/' . $archivo->ruta) }}"
                                             class="card-img-top"
                                             alt="{{ $archivo->nombre_original }}"
                                             style="height: 130px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 130px;">
                                            <i class="fa fa-file-pdf fa-3x text-danger"></i>
                                        </div>
                                    @endif
                                    <div class="card-body p-2">
                                        <p class="mb-0 small fw-semibold text-truncate" title="{{ $archivo->nombre_original }}">
                                            {{ $archivo->nombre_original }}
                                        </p>
                                        @if($tamanoKB)
                                            <small class="text-muted">{{ $tamanoKB }} KB</small>
                                        @endif
                                    </div>
                                    <div class="card-footer p-2 bg-transparent border-top">
                                        <a href="{{ asset('storage/' . $archivo->ruta) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary w-100">
                                            <i class="fa fa-download me-1"></i>Descargar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Hilo de respuestas --}}
                @if($solicitud->respuestas->isNotEmpty())
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fa fa-comments me-2 text-success"></i>Respuestas
                            <span class="badge bg-success ms-1">{{ $solicitud->respuestas->count() }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($solicitud->respuestas as $resp)
                        <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            {{-- Cabecera de la respuesta --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                                         style="width:36px;height:36px;font-size:.85rem;flex-shrink:0;">
                                        {{ strtoupper(substr($resp->usuario->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold small">{{ $resp->usuario->name ?? 'Soporte' }}</div>
                                        <small class="text-muted">{{ $resp->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                                @if($resp->estado_nuevo)
                                <span class="badge {{ $badgeEstado[$resp->estado_nuevo] ?? 'bg-secondary' }} small">
                                    <i class="fa fa-exchange-alt me-1"></i>
                                    Estado cambiado a: {{ ucfirst(str_replace('_', ' ', $resp->estado_nuevo)) }}
                                </span>
                                @endif
                            </div>
                            {{-- Contenido --}}
                            <div class="p-3 rounded bg-light border" style="white-space:pre-wrap;font-size:.95rem;line-height:1.7;">
                                {{ $resp->contenido }}
                            </div>
                            {{-- Archivos adjuntos de la respuesta --}}
                            @if($resp->archivos->isNotEmpty())
                            <div class="mt-3">
                                <small class="text-muted fw-semibold d-block mb-2">
                                    <i class="fa fa-paperclip me-1"></i>Archivos adjuntos ({{ $resp->archivos->count() }})
                                </small>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($resp->archivos as $archivo)
                                    @php $esImg = strpos($archivo->tipo_mime ?? '', 'image/') === 0; @endphp
                                    <a href="{{ asset('storage/' . $archivo->ruta) }}" target="_blank"
                                       class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                                        <i class="fa {{ $esImg ? 'fa-image' : 'fa-file' }} me-1"></i>
                                        <span class="text-truncate" style="max-width:120px;" title="{{ $archivo->nombre_original }}">
                                            {{ $archivo->nombre_original }}
                                        </span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                    @if($solicitud->estaAbierta())
                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-3">
                        <i class="fa fa-clock fa-2x text-info"></i>
                        <div>
                            <strong>En espera de respuesta</strong>
                            <p class="mb-0 small text-muted">Tu solicitud fue recibida. Nuestro equipo te responderá a la brevedad.</p>
                        </div>
                    </div>
                    @endif
                @endif

                {{-- Panel de administrador: formulario de respuesta --}}
                @if($esAdmin)
                <div class="card border-0 shadow-sm border-start border-4 border-warning">
                    <div class="card-header bg-warning bg-opacity-10 border-bottom border-warning border-opacity-25">
                        <h6 class="mb-0 fw-semibold text-warning-emphasis">
                            <i class="fa fa-shield-alt me-2"></i>Panel Administrador — Registrar Respuesta
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('soporte.responder', $solicitud->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="contenido" class="form-label fw-semibold">Respuesta</label>
                                <textarea name="contenido" id="contenido" class="form-control" rows="6"
                                    placeholder="Escribe la respuesta para el usuario..."
                                    required minlength="5">{{ old('contenido') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="estado_nuevo" class="form-label fw-semibold">Cambiar estado (opcional)</label>
                                <select name="estado_nuevo" id="estado_nuevo" class="form-select">
                                    <option value="">-- Sin cambio de estado --</option>
                                    <option value="abierta"     {{ $solicitud->estado === 'abierta'     ? 'selected' : '' }}>Abierta</option>
                                    <option value="en_progreso" {{ $solicitud->estado === 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                                    <option value="resuelta"    {{ $solicitud->estado === 'resuelta'    ? 'selected' : '' }}>Resuelta</option>
                                    <option value="cerrada"     {{ $solicitud->estado === 'cerrada'     ? 'selected' : '' }}>Cerrada</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-paperclip me-1"></i>Adjuntar archivos (opcional)
                                </label>
                                <input type="file" name="archivos[]" class="form-control"
                                       multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                                <small class="text-muted">PDF, JPG, PNG, DOC, DOCX, XLS, XLSX — máx. 5 MB c/u</small>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fa fa-paper-plane me-2"></i>Enviar Respuesta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

            </div>{{-- /col-lg-8 --}}

            {{-- ── Sidebar de detalles ────────────────────────────────────────── --}}
            <div class="col-lg-4">

                {{-- Info del ticket --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fa fa-info-circle me-2 text-primary"></i>Información del Ticket
                        </h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                            <small class="text-muted">Número</small>
                            <code class="text-primary">{{ $solicitud->numero_ticket }}</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                            <small class="text-muted">Tipo</small>
                            <span class="small fw-semibold">
                                <i class="fa {{ $iconTipo[$solicitud->tipo_solicitud] ?? 'fa-tag' }} me-1 text-muted"></i>
                                {{ $labelTipo[$solicitud->tipo_solicitud] ?? ucfirst($solicitud->tipo_solicitud) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                            <small class="text-muted">Prioridad</small>
                            <span class="badge {{ $badgePrioridad[$solicitud->prioridad] ?? 'bg-secondary' }}">
                                {{ ucfirst($solicitud->prioridad) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                            <small class="text-muted">Estado</small>
                            <span class="badge {{ $badgeEstado[$solicitud->estado] ?? 'bg-secondary' }}">
                                <i class="fa {{ $iconEstado[$solicitud->estado] ?? 'fa-circle' }} me-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                            <small class="text-muted">Creado</small>
                            <small>{{ $solicitud->created_at->format('d/m/Y H:i') }}</small>
                        </li>
                        @if($solicitud->updated_at->ne($solicitud->created_at))
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                            <small class="text-muted">Actualizado</small>
                            <small>{{ $solicitud->updated_at->format('d/m/Y H:i') }}</small>
                        </li>
                        @endif
                        @if($esAdmin && $solicitud->usuario)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                            <small class="text-muted">Usuario</small>
                            <small class="fw-semibold">{{ $solicitud->usuario->name }}</small>
                        </li>
                        @endif
                    </ul>
                </div>

                {{-- Cambiar estado (solo admin) --}}
                @if($esAdmin)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fa fa-exchange-alt me-2 text-warning"></i>Cambiar Estado
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('soporte.cambiar-estado', $solicitud->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <select name="estado" class="form-select">
                                    <option value="abierta"     {{ $solicitud->estado === 'abierta'     ? 'selected' : '' }}>Abierta</option>
                                    <option value="en_progreso" {{ $solicitud->estado === 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                                    <option value="resuelta"    {{ $solicitud->estado === 'resuelta'    ? 'selected' : '' }}>Resuelta</option>
                                    <option value="cerrada"     {{ $solicitud->estado === 'cerrada'     ? 'selected' : '' }}>Cerrada</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-outline-warning w-100">
                                <i class="fa fa-save me-2"></i>Actualizar Estado
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Acciones --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fa fa-tools me-2 text-secondary"></i>Acciones
                        </h6>
                    </div>
                    <div class="card-body d-grid gap-2">
                        <a href="{{ route('soporte.mis-solicitudes') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-list me-2"></i>Ver Todas Mis Solicitudes
                        </a>
                        @if($solicitud->estaAbierta())
                        <a href="{{ route('soporte.index') }}" class="btn btn-outline-primary">
                            <i class="fa fa-plus me-2"></i>Nueva Solicitud
                        </a>
                        @endif
                    </div>
                </div>

            </div>{{-- /col-lg-4 --}}

        </div>{{-- /row --}}
    </div>{{-- /col-12 --}}
</div>{{-- /row --}}

@endsection
