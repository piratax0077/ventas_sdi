
@extends("maestra")
@section("titulo", "Planes de Software - Catálogo")
@section("contenido")
    <div class="row">
        <div class="col-12">
            {{-- Header profesional --}}
            <div class=" py-4 d-flex align-items-center justify-content-between d-inline">
                    <div>
                        <h2 class="mb-1 fw-bold text-primary align-items-center justify-content-between">
                            <i class="fa fa-shopping-cart me-2"></i>
                                Catálogo de Planes
                        </h2>
                        <p class="mb-0 opacity-75">Explora y selecciona los mejores planes para tus necesidades</p>
                    </div>
                </div>

            @include("notificacion")

            {{-- Acciones en carrito --}}
            <div class="mb-4 d-flex gap-2 justify-content-end mt-2">
                <button onclick="irAlCarrito()" class="btn btn-blue mx-1 btn-lg position-relative">
                    <i class="fa fa-shopping-cart me-2"></i> Mi Carrito
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="carritoCount">
                        0
                    </span>
                </button>
                <button class="btn btn-danger btn-lg mx-1" onclick="vaciarCarrito()">
                    <i class="fa fa-trash me-2"></i> Vaciar Carrito
                </button>
            </div>

            {{-- Selección de Cliente --}}
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 fw-semibold">
                        Cliente
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex gap-2 align-items-end">
                        <div class="flex-grow-1">
                            <label for="clienteCatalogo" class="form-label fw-semibold mb-2">
                                Seleccionar Cliente
                            </label>
                            <select class="form-select" id="clienteCatalogo" name="id_cliente">
                                <option value="">-- Seleccione un cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }} {{ $cliente->primer_apellido }}</option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{ route('clientes.create') }}" class="btn btn-blue pt-1 pb-2 btn-sm" title="Agregar nuevo cliente">
                            <i class="fa fa-plus me-1"></i> Nuevo Cliente
                        </a>
                    </div>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 fw-semibold">
                        Filtros de Búsqueda
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="filtroFrecuencia" class="form-label fw-semibold">
                                <i class="fa fa-clock me-1 text-primary"></i> Frecuencia
                            </label>
                            <select class="form-select" id="filtroFrecuencia" style="padding: 0.75rem 0.5rem;">
                                <option value="">Todas las frecuencias</option>
                                <option value="mensual">Mensual</option>
                                <option value="trimestral">Trimestral</option>
                                <option value="semestral">Semestral</option>
                                <option value="anual">Anual</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filtroPrecio" class="form-label fw-semibold">
                                <i class="fa fa-dollar-sign me-1 text-primary"></i> Rango de Precio
                            </label>
                            <input type="range" class="form-range" id="filtroPrecio" min="0" max="500000" value="500000" style="height: 0.5rem;">
                            <h6 class="text-muted d-block mt-2">Hasta $<span id="precioMaximoMostrado" class="fw-bold text-primary">500.000</span></h6>
                        </div>
                        <div class="col-md-4">
                            <label for="busqueda" class="form-label fw-semibold">
                                <i class="fa fa-search me-1 text-primary"></i> Buscar Plan
                            </label>
                            <input type="text" class="form-control" id="busqueda" placeholder="Nombre o descripción..." style="padding: 0.75rem 0.5rem;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grid de Planes --}}
            <div class="row g-5 mb-5" id="containerPlanes">
                @forelse($planes as $plan)
                    <div class="col-md-6 col-lg-4 plan-card" data-frecuencia="{{$plan->frecuencia}}" data-precio="{{$plan->precio}}" data-nombre="{{strtolower($plan->nombre)}}">
                        <div class="card h-100 border-0 shadow transition-all {{$plan->estado == 0 ? '' : 'opacity-50'}}">
                            {{-- Header de la tarjeta --}}
                            <div class="card-header  py-3" style="background: {{$plan->estado == 0 ? : '#fff'}};">
                                <h4 class="mb-0 text-primary fw-bold">{{$plan->nombre}}</h4>
                            </div>

                            {{-- Cuerpo de la tarjeta --}}
                            <div class="card-body d-flex flex-column">
                                {{-- Descripción --}}
                                <p class="card-text text-muted small mb-3">{{$plan->descripcion}}</p>

                                {{-- Precio --}}
                                <div class="mb-3 pb-3 border-bottom">
                                    <h3 class="mb-1">
                                        <span class=" fw-bold">${{number_format($plan->precio, 0, ',', '.')}}</span>
                                        <span class="badge bg-light-blue text-dark ms-2">{{ucfirst($plan->frecuencia)}}</span>
                                    </h3>
                                </div>

                                {{-- Características --}}
                                <div class="mb-3">
                                    <h5 class="fw-semibold mb-2">
                                        Características
                                    </h5>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg-light text-dark">
                                            <i class="fa fa-user me-1 text-primary"></i> {{$plan->cantidad_usuarios}} usuario(s)
                                        </span>
                                        <span class="badge bg-light text-primary">
                                            <i class="fa fa-mobile me-1"></i> {{$plan->cantidad_dispositivos}} dispositivo(s)
                                        </span>
                                    </div>
                                </div>

                                {{-- Features --}}
                                @if(is_array($plan->features) && count($plan->features) > 0)
                                    <div class="mb-3">
                                        <h5 class="fw-semibold mb-2">
                                            Incluye
                                        </h5>
                                        <ul class="ml-0 pl-0 mb-0">
                                            @foreach($plan->features as $feature)
                                                <li class=" mb-1">
                                                    <i class="fa fa-check"></i> {{$feature}}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Estado --}}
                                <div class="mb-3 mt-auto">
                                    @if($plan->estado)
                                        <span class="badge bg-success text-white"> Plan disponible</span>
                                    @else
                                        <span class="badge bg-danger text-white">Plan no disponible</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Footer de la tarjeta --}}
                            <div class="card-footer bg-light border-top">
                                @if($plan->estado)
                                    <button class="btn btn-blue w-100 btn btn-lg fw-semibold" onclick="agregarAlCarrito(this, {{$plan->id}}, '{{addslashes($plan->nombre)}}', {{$plan->precio}})">
                                                    <i class="fa fa-check me-1"></i>
                                         Contratar
                                    </button>
                                @else
                                    <button class="btn btn-secondary w-100 btn-sm" disabled>
                                        <i class="fa fa-lock me-2"></i> No disponible
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow bg-light">
                            <div class="card-body py-5 text-center">
                                <div style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;">
                                    <i class="fa fa-inbox"></i>
                                </div>
                                <h5 class="text-muted mb-2"> Sin planes disponibles</h5>
                                <p class="text-muted mb-0"> No hay planes de software disponibles en este momento.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>

        // document.getElementById('filtroFrecuencia').addEventListener('change', filtrarPlanes);
        document.getElementById('filtroPrecio').addEventListener('input', function() {
            document.getElementById('precioMaximoMostrado').textContent = parseInt(this.value).toLocaleString('es-CL');
            filtrarPlanes();
        });
        document.getElementById('busqueda').addEventListener('input', filtrarPlanes);

        function filtrarPlanes() {
            const frecuencia = document.getElementById('filtroFrecuencia').value;
            const precioMax = parseFloat(document.getElementById('filtroPrecio').value);
            const busqueda = document.getElementById('busqueda').value.toLowerCase();

            document.querySelectorAll('.plan-card').forEach(card => {
                let mostrar = true;

                if (frecuencia && card.dataset.frecuencia !== frecuencia) {
                    mostrar = false;
                }

                if (parseFloat(card.dataset.precio) > precioMax) {
                    mostrar = false;
                }

                if (busqueda && !card.dataset.nombre.includes(busqueda)) {
                    mostrar = false;
                }

                card.style.display = mostrar ? 'block' : 'none';
            });
        }

        // Carrito
        let carrito = JSON.parse(localStorage.getItem('carrito-planes')) || [];

        function agregarAlCarrito(btn, id, nombre, precio) {
            const idCliente = $('#clienteCatalogo').val();
            if (!idCliente) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cliente requerido',
                    text: 'Seleccione un cliente antes de agregar planes al carrito.',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            const existente = carrito.find(item => item.id === id);

            if (existente) {
                existente.cantidad += 1;
            } else {
                carrito.push({
                    id: id,
                    nombre: nombre,
                    precio: precio,
                    cantidad: 1
                });
            }

            localStorage.setItem('carrito-planes', JSON.stringify(carrito));
            actualizarContadorCarrito();

            // Enviar al backend para guardar en sesión PHP
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('tipo', 'membresia');
            formData.append('id', id);
            formData.append('id_cliente', idCliente);

            fetch("{{ route('agregarProductoVenta') }}", {
                method: 'POST',
                body: formData
            });

            // Feedback visual en el botón
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-check"></i> Agregado!';
            btn.classList.replace('btn-primary', 'btn-success');
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-cart-plus"></i> Agregar al Carrito';
                btn.classList.replace('btn-success', 'btn-primary');
            }, 1500);

            // Alerta de éxito
            Swal.fire({
                icon: 'success',
                title: '¡Agregado!',
                text: nombre + ' agregado al carrito',
                timer: 1500,
                showConfirmButton: false
            });
        }

        function actualizarContadorCarrito() {
            const total = carrito.reduce((sum, item) => sum + item.cantidad, 0);
            document.getElementById('carritoCount').textContent = total;
        }

        function vaciarCarrito() {
            Swal.fire({
                title: '¿Vaciar el carrito?',
                text: 'Esto eliminará todos los planes agregados',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, vaciar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) return;

                carrito = [];
                localStorage.removeItem('carrito-planes');
                actualizarContadorCarrito();

                // También limpiar la sesión del servidor
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('accion', 'cancelar');
                fetch("{{ route('terminarOCancelarVenta') }}", {
                    method: 'POST',
                    body: formData
                });

                Swal.fire('¡Vaciado!', 'El carrito ha sido limpiado', 'success');
            });
        }

        // Inicializar contador
        actualizarContadorCarrito();
    </script>

    @push('scripts')
    <script>
        $(document).ready(function () {
            // Registrar DESPUÉS del $(document).ready() global de maestra.blade.php
            // que reinicializa Select2, para que los listeners no sean destruidos.
            $('#filtroFrecuencia').on('change.select2', filtrarPlanes);

            // Al cambiar el cliente, persiste su id para usarlo al ir al carrito
            $('#clienteCatalogo').on('change.select2', function () {
                localStorage.setItem('carrito-cliente', this.value);
            });
        });

        function irAlCarrito() {
            const idCliente = $('#clienteCatalogo').val();
            if (!idCliente) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cliente requerido',
                    text: 'Seleccione un cliente antes de ir al carrito.',
                    confirmButtonText: 'Entendido'
                });
                return;
            }
            window.location.href = "{{ route('vender.index') }}?id_cliente=" + idCliente;
        }
    </script>
    @endpush

    @push('styles')
    <style>
        /* Estilos profesionales para catálogo de planes */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }

        /* Header de tarjetas de planes */
        .plan-card .card-header {
            background: linear-gradient(135deg, #8e8f96 0%, #764ba2 100%);
            color: white;
            padding: 1.75rem !important;
        }

        .plan-card .card-header h5 {
            font-size: 1.5rem;
        }

        /* Header de filtros y otros cards */
        .card > .card-header.bg-light {
            background: linear-gradient(135deg, #f5f7fa 0%, #f1f3f5 100%) !important;
            border-bottom: 2px solid #e9ecef;
            color: inherit;
        }

        .card-body {
            padding: 2rem;
            font-size: 1.05rem;
        }

        .plan-card .card-body {
            padding: 2rem;
        }

        .plan-card .card-body h6 {
            font-size: 1.1rem;
        }

        .plan-card .card-text {
            font-size: 1rem;
        }

        .plan-card h4 {
            font-size: 1.75rem;
        }

        .badge {
            padding: 0.625rem 1rem;
            font-weight: 600;
            border-radius: 0.35rem;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .badge:hover {
            transform: scale(1.05);
        }

        .form-select,
        .form-control {
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-lg:hover:not(:disabled) {
            transform: translateY(-2px);
        }

        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #5a6bcf 0%, #6a3a92 100%);
        }

        /* Lista de features */
        .card-body ul li {
            transition: all 0.2s ease;
        }

        .card-body ul li:hover {
            transform: translateX(3px);
        }

        /* Animación suave para tarjetas */
        .transition-all {
            transition: all 0.3s ease;
        }

        /* Grid responsive */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }

            .plan-card .card-body {
                padding: 1.5rem;
            }

            .btn-lg {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .plan-card .card-header h5 {
                font-size: 1.25rem;
            }

            .plan-card h4 {
                font-size: 1.5rem;
            }
        }




        .row.g-4 {
                gap: 1rem !important;
            }

        /* Estilos para slider del rango de precio */
        .form-range::-webkit-slider-thumb {
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #8e8e91 0%, #764ba2 100%);
            cursor: pointer;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
        }

        .form-range::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #858688 0%, #764ba2 100%);
            cursor: pointer;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
        }

        /* Efecto de carga suave */
        .plan-card {
            animation: fadeInUp 0.3s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    @endpush

@endsection
