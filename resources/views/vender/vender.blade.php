
@extends("maestra")
@section("titulo", "Realizar venta")
@section("contenido")
    <div class="row">
        <div class="col-12">
            {{-- Header profesional --}}

             <div class=" py-4 d-flex align-items-center justify-content-between d-inline">
                    <div>
                        <h2 class="mb-1 fw-bold text-primary align-items-center justify-content-between">
                           <i class="fa fa-cart-plus me-2"></i>
                              Generar Venta
                        </h2>
                        <p class="mb-0 opacity-75">Gestionar y procesar ventas de productos y membresías</p>
                    </div>
                </div>

            @include("notificacion")
            <div class="row g-4 mb-4">
                {{-- Tipo de Venta --}}
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 fw-semibold">
                              Tipo de Venta
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <select name="tipoventa" id="tipoventa" class="form-control form-control-lg">
                                    <option value="0">Seleccione tipo de venta</option>
                                    <option value="producto"><i class="fa fa-box"></i> Producto</option>
                                    <option value="membresia"><i class="fa fa-id-card"></i> Membresía</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Seleccionar Cliente --}}
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 fw-semibold">
                              Cliente
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{route("terminarOCancelarVenta")}}" method="post">
                                @csrf
                                <div class="form-group mb-3">
                                    <select required class="form-control form-control-lg" name="id_cliente" id="id_cliente">
                                        <option value="">-- Seleccione un cliente --</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{$cliente->id}}" {{ request('id_cliente') == $cliente->id ? 'selected' : '' }}>{{$cliente->nombre}} {{ $cliente->primer_apellido }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(session("productos") !== null)
                                    <div class="d-grid gap-2">
                                        <button name="accion" value="terminar" type="submit" class="btn btn-success btn-lg">
                                            <i class="fa fa-check me-2"></i> Terminar Venta
                                        </button>
                                        <button name="accion" value="cancelar" type="submit" formnovalidate class="btn btn-outline-danger">
                                            <i class="fa fa-times-circle me-2"></i> Cancelar
                                        </button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Agregar Productos --}}
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa fa-plus-circle me-2 text-success"></i> Agregar Artículo
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{route("agregarProductoVenta")}}" method="post" id="formAgregarProducto">
                                @csrf
                                <input type="hidden" name="tipo" id="tipoVentaHidden" value="producto">
                                <div class="form-group" id="campoCodigo">
                                    {{-- <label for="codigo" class="form-label">Código de barras</label> --}}
                                    <input id="codigo" autocomplete="off" autofocus name="codigo" type="text"
                                           class="form-control form-control-lg"
                                           placeholder="Escanear código...">
                                </div>
                                <div class="form-group d-none" id="campoMembresia">
                                    <label class="form-label">Membresía</label>
                                    <a href="{{ route('planes.catalogo') }}" class="btn btn-blue w-100 btn-lg">
                                        Ver Catálogo de Planes
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @if(session("productos") !== null)
                {{-- Resumen del Total --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #898b93 0%, #764ba2 100%);">
                            <div class="card-body py-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-white">
                                        <p class="mb-1 opacity-75 small">TOTAL A PAGAR</p>
                                        <h2 class="mb-0 fw-bold">${{number_format($total, 2)}}</h2>
                                    </div>
                                    <div class="text-white opacity-25" style="font-size: 3rem;">
                                        <i class="fa fa-dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabla de artículos agregados --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light border-bottom">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fa fa-shopping-cart me-2 text-primary"></i> Artículos Agregados
                                    <span class="badge bg-primary ms-2"> {{ count(session("productos")) }}</span>
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Código / ID</th>
                                            <th>Descripción / Nombre</th>
                                            <th class="text-end">Precio Unitario</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-center">Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(session("productos") as $item)
                                            @php
                                                $esMembresia = isset($item->tipo) && $item->tipo === 'membresia';
                                                $precio = $esMembresia ? $item->precio : $item->precio_venta;
                                            @endphp
                                            <tr class="align-middle">
                                                <td>
                                                    @if($esMembresia)
                                                        <span class="badge bg-primary"><i class="fa fa-id-card me-1"></i>Membresía</span>
                                                    @else
                                                        <span class="badge bg-secondary"><i class="fa fa-box me-1"></i>Producto</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($esMembresia)
                                                        <small class="text-muted fw-semibold">#{{$item->id}}</small>
                                                    @else
                                                        <code class="text-primary">{{$item->codigo_barras}}</code>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($esMembresia)
                                                        <div class="fw-semibold">{{$item->nombre}}</div>
                                                        <small class="text-muted">{{ucfirst($item->frecuencia)}}</small>
                                                    @else
                                                        <div class="fw-semibold">{{$item->descripcion}}</div>
                                                    @endif
                                                </td>
                                                <td class="text-end fw-semibold">${{number_format($precio, 2)}}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark">{{$item->cantidad}}</span>
                                                </td>
                                                <td class="text-end fw-bold">${{number_format($precio * $item->cantidad, 2)}}</td>
                                                <td class="text-center">
                                                    <form action="{{route("quitarProductoDeVenta")}}" method="post" style="display: inline;" class="form-eliminar">
                                                        @method("delete")
                                                        @csrf
                                                        <input type="hidden" name="indice" value="{{$loop->index}}">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar artículo" onclick="confirmarEliminacion(event, '{{$esMembresia ? $item->nombre : $item->descripcion}}')">
                                                            <i class="fa fa-trash"></i>
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
                    </div>
                </div>
            @else
                {{-- Mensaje cuando no hay productos --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm bg-light">
                            <div class="card-body py-5 text-center">
                                <div style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;">
                                    <i class="fa fa-inbox"></i>
                                </div>
                                <h5 class="text-muted mb-2"> Sin productos agregados</h5>
                                <p class="text-muted mb-0">
                                    Escanea un código de barras o selecciona una membresía para comenzar
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        /**
         * Confirmar eliminación de un artículo usando SweetAlert2
         */
        function confirmarEliminacion(event, nombreArticulo) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: '¿Eliminar artículo?',
                html: `<p>¿Está seguro de que desea eliminar:</p><strong>${nombreArticulo}</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-trash me-2"></i> Sí, eliminar',
                cancelButtonText: '<i class="fa fa-times me-2"></i> Cancelar',
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


        // Limpiar carrito al cancelar venta
        document.querySelectorAll('button[value="cancelar"]').forEach(btn => {
            btn.addEventListener('click', function() {
                localStorage.removeItem('carrito-planes');
            });
        });
    </script>

    @push('scripts')
    <script>
        $(document).ready(function () {
            // Se ejecuta DESPUÉS del $(document).ready() global de maestra.blade.php
            // que reinicializa Select2 en todos los selects. Así el listener
            // se adjunta sobre la instancia final y no es destruido.
            $('#tipoventa').on('change.select2', function () {
                const tipo = this.value;
                const campoCodigo = document.getElementById('campoCodigo');
                const campoMembresia = document.getElementById('campoMembresia');
                const inputCodigo = document.getElementById('codigo');

                document.getElementById('tipoVentaHidden').value = tipo;

                if (tipo === 'membresia') {
                    campoCodigo.classList.add('d-none');
                    campoMembresia.classList.remove('d-none');
                    inputCodigo.required = false;
                } else {
                    campoCodigo.classList.remove('d-none');
                    campoMembresia.classList.add('d-none');
                    inputCodigo.required = true;
                    inputCodigo.focus();
                }
            });
        });
    </script>
    @endpush

    @push('styles')
    <style>
        /* Estilos profesionales para la vista de ventas */
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

        .form-control-lg {
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-control-lg:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            border-radius: 0.35rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
        }

        .bg-gradient {
            background-attachment: fixed;
        }

        /* Animación suave para los inputs */
        input[type="text"],
        select {
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        /* Mejora de legibilidad en la tabla */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        code {
            background-color: rgba(102, 126, 234, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.25rem;
            }

            .table {
                font-size: 0.875rem;
            }
        }
    </style>
    @endpush

@endsection
