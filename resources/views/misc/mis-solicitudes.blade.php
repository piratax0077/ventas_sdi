@extends("maestra")
@section("titulo", "Mis Solicitudes de Soporte")
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
        'en_progreso' => 'fa-spinner',
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
@endphp

<div class="row">
    <div class="col-12">
        {{-- Header --}}
            <div class="py-4 d-flex align-items-center justify-content-between">
                <div class="text-dark">
                    <h2 class="mb-1 fw-bold">
                        Mis Solicitudes
                    </h2>
                    <p class="mb-0 opacity-75">Historial y estado de tus solicitudes de soporte</p>
                </div>
                <a href="{{ route('soporte.index') }}" class="btn btn-info btn-lg">
                    <i class="fa fa-plus me-2"></i> Nueva Solicitud
                </a>
            </div>
    

        @include("notificacion")

        {{-- Resumen de estados --}}
        @php
            $totales = [
                'abierta'     => $solicitudes->getCollection()->where('estado', 'abierta')->count(),
                'en_progreso' => $solicitudes->getCollection()->where('estado', 'en_progreso')->count(),
                'resuelta'    => $solicitudes->getCollection()->where('estado', 'resuelta')->count(),
                'cerrada'     => $solicitudes->getCollection()->where('estado', 'cerrada')->count(),
            ];
        @endphp
        <div class="row g-3 mb-5">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body p-2">
                      <h1 class="fs-3 fw-bold text-warning">{{ $totales['abierta'] }}</h1>
                        <h5 class="text-warning">Abiertas</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body p-2">
                        <h1 class="fs-3 fw-bold text-primary">{{ $totales['en_progreso'] }}</h1>
                        <h5 class="text-primary">En Progreso</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body p-2">
                        <h1 class="fs-3 fw-bold text-success">{{ $totales['resuelta'] }}</h1>
                        <h5 class="text-success">Resueltas</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body p-2">
                        <h1 class="fs-3 fw-bold text-secondary">{{ $totales['cerrada'] }}</h1>
                        <h5 class="text-secondary">Cerradas</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de solicitudes --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-bottom d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    Solicitudes
                    <span class="badge bg-primary text-white ms-1">{{ $solicitudes->total() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                @if($solicitudes->isEmpty())
                    <div class="py-5 text-center">
                        <div style="font-size: 4rem; color: #ccc;"><i class="fa fa-inbox"></i></div>
                        <h5 class="text-muted mt-3">Sin solicitudes</h5>
                        <p class="text-muted">Aún no has enviado ninguna solicitud de soporte.</p>
                        <a href="{{ route('soporte.index') }}" class="btn btn-info mt-2">
                            <i class="fa fa-plus me-2"></i> Crear Primera Solicitud
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Ticket</th>
                                    <th>Tipo</th>
                                    <th>Asunto</th>
                                    <th class=" text-center">Prioridad</th>
                                    <th class=" text-center">Estado</th>
                                    <th>Fecha</th>
                                    <th class=" text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($solicitudes as $solicitud)
                                    <tr class="align-middle">
                                        <td class="ps-3">
                                            <code class="text-primary">{{ $solicitud->numero_ticket }}</code>
                                        </td>
                                        <td>
                                            <p>
                                                {{ $labelTipo[$solicitud->tipo_solicitud] ?? ucfirst($solicitud->tipo_solicitud) }}
                                            </p>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-truncate text-capitalize" style="max-width: 250px;" title="{{ $solicitud->asunto }}">
                                                {{ $solicitud->asunto }}
                                            </div>
                                            @if($solicitud->archivos_count ?? $solicitud->archivos->count() ?? 0)
                                                <p class="text-muted">
                                                    <i class="fa fa-paperclip me-1"></i>{{ $solicitud->archivos->count() }} archivo(s)
                                                </p>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge text-white {{ $badgePrioridad[$solicitud->prioridad] ?? 'bg-secondary' }}">
                                                {{ ucfirst($solicitud->prioridad) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge text-white {{ $badgeEstado[$solicitud->estado] ?? 'bg-secondary' }}">
                                                <i class="fa {{ $iconEstado[$solicitud->estado] ?? 'fa-circle' }} me-1"></i>
                                                {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small
                                                {{ $solicitud->created_at->format('d/m/Y') }}<br>
                                                <span class="opacity-75">{{ $solicitud->created_at->format('H:i') }}</span>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('soporte.show', $solicitud->id) }}"
                                               class="btn btn-icon btn-blue" title="Ver detalle">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    @if($solicitudes->hasPages())
                        <div class="px-3 py-3 border-top">
                            {{ $solicitudes->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
