
@extends("maestra")
@section("titulo", "Clientes")
@section("contenido")
    <div class="row">
        <div class="col-12">
            {{-- Header profesional --}}
              <div class=" py-4 d-flex align-items-center justify-content-between d-inline">
                    <div>
                        <h2 class="mb-1 fw-bold text-primary align-items-center justify-content-between">
                          <i class="fa fa-users me-2"></i>
                            Clientes
                        </h2>
                        <p class="mb-0 opacity-75">Gestiona la base de datos de clientes</p>
                    </div>
                    
                    <div class="mb-4 d-flex justify-content-between align-items-center d-inline">
                         <a href="{{route("clientes.create")}}"  class="btn btn-info btn-lg">
                            <i class="fa fa-plus me-2"></i> Añadir Cliente
                        </a>
                    </div>
             
                </div>


            @include("notificacion")

       

            {{-- Tabla de clientes --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 fw-semibold d-inline">
                        Listado de Clientes
                        <h6 class="badge bg-primary ms-2 text-white">{{ count($clientes) }}</h6>
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>RUT</th>
                                <th>Nombre</th>
                                <th>Apellido Paterno</th>
                                <th>Apellido Materno</th>
                                <th>Teléfono</th>
                                <th>Rol</th>
                                <th>Dirección</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($clientes as $cliente)
                                <tr class="align-middle">
                                    <td>
                                        <code class="text-primary font-weight-bold">{{$cliente->rut}}</code>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-capitalize">{{$cliente->nombre}}</div>
                                    </td>
                                    <td>
                                        <p class="text-capitalize">{{$cliente->primer_apellido}}</p>
                                    </td>
                                    <td>
                                        <p class="text-capitalize">{{$cliente->segundo_apellido}}</p>
                                    </td>
                                    <td>
                                        <a href="tel:{{$cliente->telefono}}" class="text-primary text-decoration-none">
                                            <i class="fa fa-phone me-1 text-primary font-weight-bold"></i> {{$cliente->telefono}}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-azul text-white">{{$cliente->rol->name}}</span>
                                    </td>
                                    <td>
                                        <p class="text-capitalize">{{Str::limit($cliente->direccion, 40)}}</p>
                                    </td>
                                    <td>
                                            <button class="btn btn-icon btn-sucess" title="Ver estado de cuenta" data-bs-toggle="modal" data-bs-target="#modalEstadoCuenta{{$cliente->id}}" onclick="cargarEstadoCuenta({{$cliente->id}}, '{{$cliente->nombre}}')">
                                                <i class="fa fa-file-invoice-dollar"></i>
                                            </button>
                                            <a class="btn  btn-icon btn-warning" href="{{route("clientes.edit",[$cliente])}}" title="Editar cliente">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{route("clientes.destroy", [$cliente])}}" method="post" style="display:inline" class="form-eliminar">
                                                @method("delete")
                                                @csrf
                                                <button type="submit" class="btn btn-icon btn-danger" title="Eliminar cliente" onclick="confirmarEliminacionCliente(event, '{{$cliente->nombre}} {{$cliente->primer_apellido}}')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div style="color: #ccc; margin-bottom: 1rem; font-size: 3rem;">
                                            <i class="fa fa-inbox"></i>
                                        </div>
                                        <h5 class="text-muted mb-2"> Sin clientes registrados</h5>
                                        <p class="text-muted mb-3"> No hay clientes disponibles en este momento.</p>
                                        <a href="{{route("clientes.create")}}" class="btn btn-primary">
                                            <i class="fa fa-plus-circle me-2"></i> Crear Cliente Ahora
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

    {{-- Modal para Ver Estado de Cuenta --}}
    @foreach($clientes as $cliente)
        <div class="modal fade" id="modalEstadoCuenta{{$cliente->id}}" tabindex="-1" aria-labelledby="modalLabel{{$cliente->id}}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                        <h5 class="modal-title fw-bold" id="modalLabel{{$cliente->id}}">
                            <i class="fa fa-file-invoice-dollar me-2"></i>Estado de Cuenta
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6 class="text-muted mb-2">Cliente</h6>
                                <div class="fw-semibold">{{$cliente->nombre}} {{$cliente->primer_apellido}} {{$cliente->segundo_apellido}}</div>
                                <small class="text-muted">RUT: {{$cliente->rut}}</small>
                            </div>
                        </div>
                        <hr>
                        <div id="contenidoEstado{{$cliente->id}}" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">Cargando información de estado de cuenta...</p>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="descargarEstadoCuenta({{$cliente->id}}, '{{$cliente->nombre}}')">
                            <i class="fa fa-download me-2"></i>Descargar PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>

        $(document).ready(function() {
            $('.table').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-CL.json'
                },
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                columnDefs: [
                    { orderable: false, targets: -1 },
                    { searchable: false, targets: -1 }
                ]
            });
         });
        /**
         * Confirmar eliminación de un cliente usando SweetAlert2
         */
        function confirmarEliminacionCliente(event, nombreCliente) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: '¿Eliminar cliente?',
                html: `<p>¿Está seguro de que desea eliminar a:</p><strong>${nombreCliente}</strong><p><small class="text-danger">Esta acción no se puede deshacer</small></p>`,
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

        /**
         * Cargar estado de cuenta del cliente en el modal
         */
        function cargarEstadoCuenta(clienteId, nombreCliente) {
            const contenedorId = `contenidoEstado${clienteId}`;
            const contenedor = document.getElementById(contenedorId);

            // Simular carga de datos
            setTimeout(() => {
                contenedor.innerHTML = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Total Facturado</h6>
                                    <h4 class="text-primary fw-bold">$1,250,000</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Pagos Realizados</h6>
                                    <h4 class="text-success fw-bold">$850,000</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Saldo Pendiente</h6>
                                    <h4 class="text-danger fw-bold">$400,000</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Monto</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2024-01-15</td>
                                    <td><span class="badge bg-info">Factura</span></td>
                                    <td>$500,000</td>
                                    <td>$500,000</td>
                                </tr>
                                <tr>
                                    <td>2024-01-20</td>
                                    <td><span class="badge bg-success">Pago</span></td>
                                    <td>-$300,000</td>
                                    <td>$200,000</td>
                                </tr>
                                <tr>
                                    <td>2024-02-10</td>
                                    <td><span class="badge bg-info">Factura</span></td>
                                    <td>$750,000</td>
                                    <td>$950,000</td>
                                </tr>
                                <tr>
                                    <td>2024-02-25</td>
                                    <td><span class="badge bg-success">Pago</span></td>
                                    <td>-$550,000</td>
                                    <td>$400,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `;
            }, 500);
        }

        /**
         * Descargar estado de cuenta en PDF
         */
        function descargarEstadoCuenta(clienteId, nombreCliente) {
            Swal.fire({
                title: '¡Descarga iniciada!',
                html: `<p>El estado de cuenta de <strong>${nombreCliente}</strong> se está descargando.</p>`,
                icon: 'success',
                confirmButtonColor: '#667eea',
                confirmButtonText: 'Aceptar',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary btn-sm'
                }
            });
            // Aquí se implementaría la lógica real de descarga PDF
            // window.location.href = `/clientes/${clienteId}/descargar-estado-cuenta`;
        }
    </script>

    @push('styles')
    <style>
        /* Estilos profesionales para tabla de clientes */
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

        code {
            background-color: rgba(102, 126, 234, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }

        .btn-group {
            gap: 0.25rem;
        }

        .btn-outline-warning,
        .btn-outline-danger {
            transition: all 0.2s ease;
        }

        .btn-outline-warning:hover,
        .btn-outline-danger:hover {
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

        /* Links de teléfono */
        a[href^="tel:"] {
            transition: all 0.2s ease;
        }

        a[href^="tel:"]:hover {
            color: #4a68d8 !important;
            text-decoration: underline !important;
        }

        /* Responsive */
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

            .card-header {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
    @endpush

@endsection
