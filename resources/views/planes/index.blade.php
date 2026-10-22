
@extends("maestra")
@section("titulo", "Planes de Software")
@section("contenido")
    <div class="row">
        <div class="col-12">
            {{-- Header profesional --}}
            <div class=" py-4 d-flex align-items-center justify-content-between d-inline">
                    <div>
                        <h3 class="mb-1 fw-bold text-primary align-items-center justify-content-between">
                            <i class="fa fa-cube me-2"></i>
                            Planes Software
                        </h3>
                        <p class="mb-0 opacity-75">Gestiona los planes y membresías disponibles</p>
                    </div>
                    
                    <div class="mb-4 d-flex justify-content-between align-items-center d-inline">
                        <a href="{{route("planes.create")}}"  class="btn btn-info btn-lg">
                            <i class="fa fa-plus-circle me-2"></i> Agregar Producto
                        </a>
                    </div>
             
                </div>


        

            @include("notificacion")


            {{-- Tabla de planes --}}
            <div class="card shadow p-2">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 fw-semibold">
                        Listado de Planes
                        <span class="badge bg-primary text-white ms-2">{{ count($planes) }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaPlanes" class="table table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Frecuencia</th>
                                <th class="text-center">Usuarios</th>
                                <th class="text-center">Dispositivos</th>
                                <th class="text-center">Features</th>
                                <th>Estado</th>
                                <th class=" text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($planes as $plan)
                                <tr class="align-middle">
                                    <td>
                                        <div class="fw-semibold">{{$plan->nombre}}</div>
                                    </td>
                                    <td>
                                        <small>{{Str::limit($plan->descripcion, 50)}}</small>
                                    </td>
                                    <td class="text-end fw-bold">${{number_format($plan->precio, 2)}}</td>
                                    <td>
                                        <span class="badge bg-info text-white">{{ucfirst($plan->frecuencia)}}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{{$plan->cantidad_usuarios}}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{{$plan->cantidad_dispositivos}}</span>
                                    </td>
                                    <td class="text-center">
                                        @if(is_array($plan->features) && count($plan->features) > 0)
                                            <button class="btn btn-sm btn-blue" data-bs-toggle="popover"
                                                data-bs-title="Features" data-bs-content="{{implode(', ', $plan->features)}}" title="Ver características">
                                                <i class="fa fa-star me-1"></i> {{count($plan->features)}}
                                            </button>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($plan->estado)
                                            <span class="badge bg-success text-white">Activo</span>
                                        @else
                                            <span class="badge bg-danger text-white">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a class="btn btn-icon btn-warning" href="{{route("planes.edit", [$plan])}}" title="Editar plan">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{route("planes.destroy", [$plan])}}" method="post" style="display:inline">
                                                @method("delete")
                                                @csrf
                                                <button type="submit" class="btn btn-icon btn-danger" title="Eliminar plan" onclick="return confirm('¿Está seguro que desea eliminar este plan?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div style="color: #ccc; margin-bottom: 1rem; font-size: 3rem;">
                                            <i class="fa fa-inbox"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">Sin planes registrados</h5>
                                        <p class="text-muted mb-3">No hay planes disponibles en este momento.</p>
                                        <a href="{{route("planes.create")}}" class="btn btn-primary">
                                            <i class="fa fa-plus-circle me-2"></i>Crear Plan Ahora
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* Estilos profesionales para tabla de planes */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
        }

        .card-header {
            background: linear-gradient(135deg, #f5f7fa 0%, #f1f3f5 100%);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f8f9fa;
        }

        .table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #e9ecef;
        }

        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
            box-shadow: inset 0 0 10px rgba(102, 126, 234, 0.1);
        }

        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            border-radius: 0.35rem;
        }

        .btn-group {
            gap: 0.25rem;
        }

        .btn-outline-warning,
        .btn-outline-danger,
        .btn-outline-info {
            transition: all 0.2s ease;
        }

        .btn-outline-warning:hover,
        .btn-outline-danger:hover,
        .btn-outline-info:hover {
            transform: translateY(-2px);
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-lg:hover {
            transform: translateY(-2px);
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .table {
                font-size: 0.875rem;
            }

            .btn-group {
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            .btn-sm {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>
    @endpush

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })

        // Inicializar DataTables
        $(document).ready(function() {
            $('#tablaPlanes').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: 8 }
                ]
            });
        });
    </script>
@endsection
