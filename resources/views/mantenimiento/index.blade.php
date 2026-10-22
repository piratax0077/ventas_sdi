@extends("maestra")
@section("titulo", "Mantenimientos")
@section("contenido")
    @push('styles')
        <style>
            .gradient-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
            }

            .table-hover tbody tr {
                transition: background-color 0.3s ease;
            }

            .table-hover tbody tr:hover {
                background-color: #f8f9fa;
            }

            .btn-action {
                transition: all 0.3s ease;
                font-size: 0.875rem;
            }

            .btn-action:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .filter-card {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
            }

            .filter-card:hover {
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .empty-state {
                text-align: center;
                padding: 3rem 1rem;
            }

            .empty-state-icon {
                font-size: 3rem;
                color: #dee2e6;
                margin-bottom: 1rem;
            }
        </style>
    @endpush

    <div class="row">
        <div class="col-12">
            <!-- Encabezado con Gradiente -->
             <div class=" py-4 d-flex align-items-center justify-content-between d-inline">
                    <div>
                        <h2 class="mb-1 fw-bold text-primary align-items-center justify-content-between">
                            <i class="fa fa-wrench me-2"></i> Gestión de Mantenimientos
                        </h2>
                        <p class="mb-0 opacity-75">Administra todas las tareas de mantenimiento</p>
                    </div>
                    
                    <div class="mb-4 d-flex justify-content-between align-items-center d-inline">
                        <a href="{{ route('mantenimiento.create') }}" class="btn btn-info btn-lg">
                            <i class="fa fa-plus me-2"></i> Nuevo Mantenimiento
                        </a>
                    </div>
             
                </div>

            @include("notificacion")

            <!-- Filtros -->
            <div class="card border-0 shadow-sm mb-4 filter-card">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 fw-bold">
                        Filtros de Búsqueda
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="filtroTipo" class="form-label fw-semibold">Tipo de Mantenimiento</label>
                            <select class="form-select form-select-sm" id="filtroTipo">
                                <option value="">✓ Todos</option>
                                <option value="servidor">Servidor</option>
                                <option value="sistema">Sistema</option>
                                <option value="software">Software</option>
                                <option value="hardware">Hardware</option>
                                <option value="red">Red</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroEstado" class="form-label fw-semibold">Estado</label>
                            <select class="form-select form-select-sm" id="filtroEstado">
                                <option value="">✓ Todos</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="activo">Activo</option>
                                <option value="completado">Completado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="busqueda" class="form-label fw-semibold">Buscar</label>
                            <input type="text" class="form-control form-control-sm" id="busqueda" placeholder="📋 Descripción o responsable...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Mantenimientos -->
            @if(count($mantenimientos) > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0 text-primary fw-bold">
                            <i class="fa fa-list me-2"></i>Listado de Mantenimientos
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="tablaMantenciones">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Descripción</th>
                                        <th class="border-0">Tipo</th>
                                        <th class="border-0">Fecha Inicio</th>
                                        <th class="border-0">Responsable</th>
                                        <th class="border-0 text-center">Estado</th>
                                        <th class="border-0 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mantenimientos as $mantenimiento)
                                        <tr>
                                            <td class="align-middle">
                                                <strong>{{ Str::limit($mantenimiento->descripcion, 50) }}</strong>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-info text-white">{{ ucfirst($mantenimiento->tipo) }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-light text-dark">{{ $mantenimiento->fecha_inicio->format('d/m/Y H:i') }}</span>
                                            </td>
                                            <td class="align-middle">
                                                {{ $mantenimiento->responsable ?? 'N/A' }}
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge bg-{{ $mantenimiento->etiqueta_estado }}">
                                                    {{ ucfirst($mantenimiento->estado) }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                              
                                                    <a href="{{ route('mantenimiento.show', $mantenimiento->id) }}" class="btn btn-primary btn-icon btn-action" title="Ver">
                                                        <i class="fa fa-eye me-1"></i> Ver
                                                    </a>
                                                    <a href="{{ route('mantenimiento.edit', $mantenimiento->id) }}" class="btn btn-warning btn-icon btn-action" title="Editar">
                                                        <i class="fa fa-edit me-1"></i> Editar
                                                    </a>
                                                    <form action="{{ route('mantenimiento.destroy', $mantenimiento->id) }}" method="POST" class="d-inline">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-icon btn-sm btn-action" onclick="confirmarEliminacionMantenimiento(event, '{{ $mantenimiento->descripcion }}')" title="Eliminar">
                                                            <i class="fa fa-trash me-1"></i> Eliminar
                                                        </button>
                                                    </form>
                                                
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <!-- Estado Vacío -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="empty-state">
                            <h5 class="text-muted mb-2"> No hay mantenimientos registrados</h5>
                            <p class="text-muted mb-3"> Cuando registres nuevos mantenimientos, aparecerán aquí.</p>
                            <a href="{{ route('mantenimiento.create') }}" class="btn btn-info btn">
                                <i class="fa fa-plus me-2"></i> Crear Mantenimiento
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Paginación -->
            @if(count($mantenimientos) > 0)
                <div class="d-flex justify-content-center mt-4">
                    {{ $mantenimientos->links() }}
                </div>
            @endif
        </div>
    </div>
    <script>
        // Inicializar DataTables
        $(document).ready(function() {
            $('#tablaMantenciones').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                columnDefs: [
                    { orderable: false, targets: -1 },
                    { searchable: false, targets: -1 }
                ],
                order: [[0, 'asc']]
            });
        });



        /**
         * Confirmar eliminación de un mantenimiento usando SweetAlert2
         */
        function confirmarEliminacionMantenimiento(event, descripcion) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: '¿Eliminar mantenimiento?',
                html: `<p>¿Está seguro de que desea eliminar:</p><strong>${descripcion}</strong><p><small class="text-danger">Esta acción no se puede deshacer</small></p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-trash me-2"></i>Sí, eliminar',
                cancelButtonText: '<i class="fa fa-times me-2"></i>Cancelar',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger btn-sm ms-2',
                    cancelButton: 'btn btn-secondary btn-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection
