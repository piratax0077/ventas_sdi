
@extends("maestra")
@section("titulo", "Productos Software")
@section("contenido")
    <div class="row">
        <div class="col-12">
            {{-- Header profesional --}}
  
                <div class=" py-4 d-flex align-items-center justify-content-between d-inline">
                    <div>
                        <h3 class="mb-1 fw-bold text-primary align-items-center justify-content-between">
                            <i class="fa fa-cube me-2"></i>
                            Productos Software
                        </h3>
                        <p class="mb-0 opacity-75">Gestiona e administra todos tus productos de software</p>
                    </div>
                    {{-- Botón agregar y opciones --}}
            <div class="mb-4 d-flex justify-content-between align-items-center d-inline">
                <a href="{{route("productos-software.create")}}" class="btn btn-info btn-lg">
                    <i class="fa fa-plus-circle me-2"></i> Agregar Producto
                </a>
            </div>
             
                </div>
       

            @include("notificacion")

            
            {{-- Tabla de productos --}}
            <div class="card border-0 shadow p-3">
                <div class="card-header pl-0 bg-light border-bottom">
                    <h5 class="mb-0 fw-semibold">
                        Listado de Productos
                        <span class="badge text-white bg-azul mr-3 ms-2" id="productCount">{{ count($productosSoftware) }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive mt-3">
                        <table class="table table-hover mb-0" id="tableProductosSoftware">
                            <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Versión</th>
                                <th>Tipo</th>
                                <th>URL Descarga</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($productosSoftware as $producto)
                                <tr class="align-middle">
                                    <td>
                                        <div class="fw-semibold">{{$producto->nombre}}</div>
                                    </td>
                                    <td>
                                        <small >{{Str::limit($producto->descripcion, 50)}}</small>
                                    </td>
                                    <td>
                                        @if($producto->versión)
                                            <span>{{$producto->versión}}</span>
                                        @else
                                            <span >-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span>{{ucfirst($producto->tipo)}}</span>
                                    </td>
                                    <td>
                                        @if($producto->url_descarga)
                                            <a href="{{$producto->url_descarga}}" target="_blank" class="btn btn-icon btn-secondary" title="Descargar">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($producto->estado)
                                            <span class="badge bg-success text-white">Activo</span>
                                        @else
                                            <span class="badge bg-danger text-white">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        
                                            <a class="btn btn-icon btn-blue" href="{{route("productos-software.show", $producto)}}" title="Ver detalles">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a class="btn btn-icon btn-warning" href="{{route("productos-software.edit", $producto)}}" title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{route("productos-software.destroy", $producto)}}" method="post" style="display:inline">
                                                @method("delete")
                                                @csrf
                                                <button type="submit" class="btn btn-icon btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro que desea eliminar este producto?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                    
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div style="color: #ccc; margin-bottom: 1rem; font-size: 3rem;">
                                            <i class="fa fa-inbox"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">Sin productos registrados</h5>
                                        <p class="text-muted mb-3">No hay productos software disponibles en este momento.</p>
                                        <a href="{{route("productos-software.create")}}" class="btn btn-primary">
                                            <i class="fa fa-plus-circle me-2"></i>Crear Producto Ahora
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
    /* Estilos profesionales para tabla de productos */
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

    .btn-outline-info,
    .btn-outline-warning,
    .btn-outline-danger,
    .btn-outline-primary {
        transition: all 0.2s ease;
    }

    .btn-outline-info:hover,
    .btn-outline-warning:hover,
    .btn-outline-danger:hover,
    .btn-outline-primary:hover {
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

    <script>
        // Inicializar DataTables
        $(document).ready(function() {
            $('#tableProductosSoftware').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: 6 }
                ]
            });
        });
    </script>
@endsection
