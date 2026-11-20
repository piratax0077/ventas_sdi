
@extends("maestra")
@section("titulo", "Ventas")
@section("contenido")
    @push('styles')
        <style>
            .gradient-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
            }

            .btn-action {
                transition: all 0.3s ease;
                font-size: 0.875rem;
            }

            .btn-action:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .badge-status {
                padding: 0.5rem 0.75rem;
                border-radius: 0.25rem;
            }
        </style>
    @endpush

    <div class="row mt-4">
        <div class="col-12">
            <!-- Encabezado con Gradiente -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h2 class="mb-0">
                        <i class="fa fa-shopping-cart me-2"></i> Gestión de Ventas
                    </h2>
                    <p class="opacity-75">Listado completo de todas las ventas registradas</p>
                </div>
                <div class="text-end">
                    <h3 class="mb-0 font-weight-bold">{{ count($ventas) }}</h3>
                    <p class="opacity-75">Total de ventas</p>
                </div>
            </div>
          

            @include("notificacion")
            @if(count($ventas) > 0)
                <!-- Tarjeta Profesional con Tabla -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0  fw-bold">
                        Listado de Ventas
                        </h5>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">
                                            Fecha
                                        </th>
                                        <th class="border-0">
                                            Cliente
                                        </th>
                                        <th class="border-0 text-end">
                                            Total
                                        </th>
                                        <th class="border-0">
                                            Documentos
                                        </th>
                                        <th class="border-0 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ventas as $venta)
                                        <tr>
                                            <td class="align-middle">
                                                <span >
                                                    {{ $venta->created_at->format('d/m/Y H:i') }}
                                                </span>
                                            </td>
                                            <td class="align-middle fw-semibold text-capitalize">
                                                {{ $venta->cliente->nombre }} {{ $venta->cliente->primer_apellido }}
                                                <br>
                                                <p>{{ $venta->cliente->rut ?? 'N/A' }}</p>
                                            </td>
                                            <td class="align-middle text-end">
                                                <h5 class="badge bg-success f-14 text-white fs-6">
                                                    ${{ number_format($venta->total, 0, '.', ',') }}
                                                </h5>
                                            </td>
                                            <td class="align-middle">
                                          
                                                    <a class="btn btn-danger btn-sm btn-action" href="{{ route('ventas.ticket.pdf', $venta->id) }}" target="_blank" title="Descargar PDF">
                                                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                                                    </a>
                                                    {{-- <a class="btn btn-info btn-sm btn-action" href="{{route("ventas.ticket", ["id"=>$venta->id])}}" title="Imprimir Ticket">
                                                        <i class="bi bi-printer me-1"></i> Imprimir
                                                    </a> --}}
                                        
                                            </td>
                                            <td class="align-middle text-center">
                                        
                                                    <a class="btn btn-blue btn-icon btn-action" href="{{route("ventas.show", $venta)}}" title="Ver detalles">
                                                        <i class="bi bi-eye me-1"></i> 
                                                    </a>
                                                    <form action="{{route("ventas.destroy", [$venta])}}" method="post" class="d-inline">
                                                        @method("delete")
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-icon btn-action" onclick="confirmarEliminacionVenta(event, '{{ $venta->id }}')">
                                                            <i class="bi bi-trash me-1"></i>
                                                        </button>
                                                    </form>
                                            
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <!-- Estado Vacío -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        @include('partials.estado_vacio', [
                            'titulo' => 'Aún no hay ventas registradas',
                            'mensaje' => 'Cuando registres una venta, aparecerá en este listado.',
                        ])
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        // Inicializar DataTables
        $(document).ready(function() {
            $('.table').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
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
         * Confirmar eliminación de una venta usando SweetAlert2
         */
        function confirmarEliminacionVenta(event, ventaId) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: '¿Eliminar venta?',
                html: `<p>¿Está seguro de que desea eliminar esta venta?</p><p><small class="text-danger">Esta acción no se puede deshacer</small></p>`,
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
