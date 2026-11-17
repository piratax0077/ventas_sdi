@extends('maestra')
@section("titulo", "Inicio")
@section('contenido')

    {{-- Banner de bienvenida --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-4 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="text-primary">
                <h3 class="mb-1 fw-bold d-inline">
                    <i class="fa fa-hand-wave me-2"></i>
                    Bienvenido, {{ Auth::user()->name }}
                </h3>
            </div>
            <div class="text-primary">
                <p class="mb-0 opacity-75 float-md-right d-inline">
                    <i class="feather icon-calendar me-1"></i>
                    {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Módulos principales --}}
    <h6 class="text-muted fw-semibold text-uppercase mb-3 ps-1">
        <i class="fa fa-th me-1"></i> Módulos del sistema
    </h6>

    @php


        $modulos = [
            [
                'label' => 'Clientes',
                'route' => 'clientes.index',
                'icon'  => 'fa-users',
                'color' => 'info',
                'img'   => 'clientes.png',
                'desc'  => 'Base de datos de clientes',
            ],
            [
                'label' => 'Software',
                'route' => 'productos-software.index',
                'ability' => 'administrar-catalogo',
                'icon'  => 'fa-box-open',
                'color' => 'primary',
                'img'   => 'productos.png',
                'desc'  => 'Gestión del catálogo de software',
            ],
            [
                'label' => 'Planes',
                'route' => 'planes.index',
                'ability' => 'administrar-catalogo',
                'icon'  => 'fa-layer-group',
                'color' => 'primary',
                'img'   => 'membresia.png',
                'desc'  => 'Membresías y planes de servicio',
            ],
             [
                'label' => 'Registrar suscripción',
                'route' => 'vender.index',
                'icon'  => 'fa-cash-register',
                'color' => 'warning',
                'img'   => 'vender.png',
                'desc'  => 'Registrar un plan para un cliente',
            ],
            [
                'label' => 'Ventas',
                'route' => 'ventas.index',
                'icon'  => 'fa-receipt',
                'color' => 'success',
                'img'   => 'ventas.png',
                'desc'  => 'Historial y resumen de ventas',
            ],
            
            [
                'label' => 'Reportes',
                'route' => 'reportes.index',
                'ability' => 'administrar-catalogo',
                'icon'  => 'fa-chart-bar',
                'color' => 'danger',
                'img'   => 'reportes.png',
                'desc'  => 'Informes y estadísticas',
            ],
            [
                'label' => 'Mantenimiento',
                'route' => 'mantenimiento.index',
                'ability' => 'administrar-catalogo',
                'icon'  => 'fa-screwdriver-wrench',
                'color' => 'secondary',
                'img'   => 'mantencion.png',
                'desc'  => 'Soporte técnico y mantenciones',
            ],
            [
                'label' => 'Configuración',
                'route' => 'configuracion.index',
                'ability' => 'administrar-usuarios',
                'icon'  => 'fa-sliders',
                'color' => 'dark',
                'img'   => 'ajustes.png',
                'desc'  => 'Parámetros del sistema',
            ],
        ];
    @endphp

    <section class="mb-4">
        <h2 class="h5">Catálogos por línea</h2>
        <div class="d-flex flex-wrap gap-2">
            @foreach(\App\CatalogoSegmentos::LINEAS as $linea => [$nombreLinea, $publicos])
                <a class="btn btn-outline-primary" href="{{ route('productos.catalogo', ['segmento' => count($publicos) === 1 ? $linea.'.'.array_key_first($publicos) : $linea]) }}">
                    {{ $nombreLinea }}
                </a>
            @endforeach
        </div>
    </section>

    <div class="row g-4">
    @foreach($modulos as $m)
        @continue(isset($m['ability']) && Gate::denies($m['ability']))
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <a href="{{ route($m['route']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 module-card text-center p-4">

                    <div class="mb-3">
                        <img src="{{ url('/img/' . ($m['img'] ?? 'default.png')) }}"
                             alt="{{ $m['label'] }}"
                             class="module-img">
                    </div>

                    <div>
                        <h6 class="fw-semibold text-dark mb-1">
                            {{ $m['label'] }}
                        </h6>
                        <p class="text-muted small mb-0">
                            {{ $m['desc'] }}
                        </p>
                    </div>

                </div>
            </a>
        </div>

    @endforeach
</div>

@endsection

@push('scripts')
<style>
    .module-card {
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .module-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,.12) !important;
    }

    .module-card img{
        width: 30%!important;
    }

</style>
@endpush
