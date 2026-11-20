
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{env("APP_NAME")}}">
    <meta name="author" content="Parzibyte">
    <title>@yield("titulo") - {{env("APP_NAME")}}</title>
    <link href="{{url("/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{url("/css/all.min.css")}}" rel="stylesheet">
    <!-- Íconos lineales -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- jQuery para AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- select2 js -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" rel="stylesheet" />
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <!-- Dropzone para carga de archivos -->
    <link href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>

    <!-- Fuente: DM Sans local (public/fonts/dm-sans), precargada para evitar el cambio de letra al cargar -->
    <link rel="preload" href="{{ asset('fonts/dm-sans/dm-sans-latin.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- ESTILO -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            min-height: 100vh;
        }

        /* Chevron animado en menú desplegable del sidebar */
        .nav-link[data-bs-toggle="collapse"] .menu-chevron {
            transition: transform 0.25s ease;
        }
        .nav-link[data-bs-toggle="collapse"][aria-expanded="true"] .menu-chevron {
            transform: rotate(180deg);
        }
        /* Submenú sidebar */
        #submenuSoporte .nav-link:hover {
            color: #fff !important;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-azul-degradado sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <!-- imagen del logo sdi -->

                <h5 class="text-white text-center">{{env("APP_NAME")}}</h5>

                    <li class="nav-item text-center mb-3">
                        <img src="{{url("/img/logo.png")}}" alt="Logo" class="img-fluid" style="max-height: 80px;">
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right menu-icono" aria-hidden="true"></i> Login
                            </a>
                        </li>

                        @if(Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('register') }}">
                                <i class="bi bi-person-plus menu-icono" aria-hidden="true"></i> Registro
                            </a>
                        </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("home")}}">
                                <i class="bi bi-house menu-icono" aria-hidden="true"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("clientes.index")}}">
                                <i class="bi bi-people menu-icono" aria-hidden="true"></i> Clientes
                            </a>
                        </li>

                        @can('administrar-catalogo')
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("productos-software.index")}}">
                                <i class="bi bi-laptop menu-icono" aria-hidden="true"></i> Software
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("planes.index")}}">
                                <i class="bi bi-layers menu-icono" aria-hidden="true"></i> Planes
                            </a>
                        </li>
                        @endcan
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("vender.index")}}">
                                <i class="bi bi-clipboard-plus menu-icono" aria-hidden="true"></i> Registrar suscripción
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('vender.videos') }}">
                                <i class="bi bi-play-circle menu-icono" aria-hidden="true"></i> Videos para vendedores
                            </a>
                        </li>
                          <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("ventas.index")}}">
                                <i class="bi bi-graph-up-arrow menu-icono" aria-hidden="true"></i> Ventas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("productos.catalogo")}}">
                                <i class="bi bi-grid menu-icono" aria-hidden="true"></i> Catálogo de software
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("planes.catalogo")}}">
                                <i class="bi bi-card-checklist menu-icono" aria-hidden="true"></i> Planes de suscripción
                            </a>
                        </li>
                        
                        @can('administrar-catalogo')
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("mantenimiento.index")}}">
                                <i class="bi bi-tools menu-icono" aria-hidden="true"></i> Mantenimiento
                            </a>
                        </li>
                        @endcan
                        @can('administrar-usuarios')
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('administracion.videos.index') }}">
                                <i class="bi bi-camera-video menu-icono" aria-hidden="true"></i> Administración · Videos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("usuarios.index")}}">
                                <i class="bi bi-person-gear menu-icono" aria-hidden="true"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("configuracion.index")}}">
                                <i class="bi bi-gear menu-icono" aria-hidden="true"></i> Configuración
                            </a>
                        </li>
                        @endcan
                      
                      

                     

                           <li class="nav-item">
                        <a class="nav-link text-white d-flex justify-content-between align-items-center"
                           href="#submenuSoporte"
                           data-bs-toggle="collapse"
                           role="button"
                           aria-expanded="{{ request()->routeIs('soporte.*') ? 'true' : 'false' }}"
                           aria-controls="submenuSoporte">
                            <span><i class="bi bi-headset menu-icono" aria-hidden="true"></i> Soporte</span>
                            <i class="bi bi-chevron-down small menu-chevron" aria-hidden="true"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('soporte.*') ? 'show' : '' }}" id="submenuSoporte">
                            <ul class="nav flex-column ps-3 mt-1">
                                <li class="nav-item">
                                    <a class="nav-link text-white-50 py-1 {{ request()->routeIs('soporte.index') ? 'text-white fw-semibold' : '' }}"
                                       href="{{ route('soporte.index') }}">
                                        <i class="bi bi-plus-circle menu-icono" aria-hidden="true"></i> Nueva Solicitud
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white-50 py-1 {{ request()->routeIs('soporte.mis-solicitudes') ? 'text-white fw-semibold' : '' }}"
                                       href="{{ route('soporte.mis-solicitudes') }}">
                                        <i class="bi bi-list-ul menu-icono" aria-hidden="true"></i> Mis Solicitudes
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!--<li class="nav-item">
                        <a class="nav-link text-white" href="{{route("acerca_de.index")}}">
                            Acerca de
                        </a>
                    </li>-->

                        <li class="nav-item border-top border-secondary mt-3 pt-3">
                            <a class="nav-link text-white" href="{{route("perfil")}}">
                                <i class="bi bi-person-circle menu-icono" aria-hidden="true"></i> Mi Perfil
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route("logout")}}">
                                <i class="bi bi-box-arrow-right menu-icono" aria-hidden="true"></i> Salir ({{ Auth::user()->name }})
                            </a>
                        </li>
                    @endguest



                </ul>
            </div>
        </nav>

        <!-- Contenido -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
            {{-- Una vista puede ocupar todo el ancho con @section('clase_contenedor', 'container-fluid') --}}
            <div class="@yield('clase_contenedor', 'container')">
 @yield("contenido")
            </div>

        </main>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    /**
     * Inicializar Select2 globalmente en todos los selects
     */
    $(document).ready(function() {
        $('select:not([data-no-select2])').select2({
            language: 'es',
            width: '100%',
            allowClear: true,
            placeholder: function() {
                return $(this).attr('placeholder') || 'Selecciona una opción...';
            }
        });
    });
</script>
@stack('scripts')
</body>
</html>
