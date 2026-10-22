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
        $medDemos = [
            [
                'label' => 'Profesionales', 
                'route' => 'planes.index'
            ],
            [
                'label' => 
                'Instituciones', 
                'route' => 'planes.index'
            ],
            [
                'label' => 'Laboratorios', 
                'route' => 'planes.index'
            ],
            [
                'label' => 'App', 
                'route' => 'planes.index'
            ],
            [
                'label' => 'Paciente', 
                'route' => 'planes.index'
            ],
            [
                'label' => 'FMU', 
                'route' => 'mantenimiento.index'
            ],
            [
                'label' => 'S. Urgencia', 
                'route' => 'planes.index'
            ],
            [
                'label' => 'Crónicos', 
                'route' => 'planes.index'
            ],
        ];
    @endphp


    @php
        $vetDemos = [
            [
                'label' => 'Profesionales', 
                'route' => 'planes.index'
            ],
            [
                'label' => 
                'Instituciones', 
                'route' => 'planes.index'
            ],
            [
                'label' => 'Laboratorios', 
                'route' => 'planes.index'
            ],
            [
                'label' => 'App', 
                'route' => 'planes.index'
            ],
            [
                'label' => 'Mascotas', 
                'route' => 'planes.index'
            ],
            [
                'label' => 'FVU', 
                'route' => 'planes.index'
            ],
            [
                'label' => 'S. Urgencia', 
                'route' => 'planes.index'
            ],
            [
                'label' => 'Crónicos', 
                'route' => 'planes.index'
            ],
        ];
    @endphp

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
                'label' => 'Productos',
                'route' => 'productos-software.index',
                'icon'  => 'fa-box-open',
                'color' => 'primary',
                'img'   => 'productos.png',
                'desc'  => 'Gestión de productos físicos',
            ],
            [
                'label' => 'Planes',
                'route' => 'planes.index',
                'icon'  => 'fa-layer-group',
                'color' => 'primary',
                'img'   => 'membresia.png',
                'desc'  => 'Membresías y planes de servicio',
            ],
             [
                'label' => 'Vender',
                'route' => 'vender.index',
                'icon'  => 'fa-cash-register',
                'color' => 'warning',
                'img'   => 'vender.png',
                'desc'  => 'Punto de venta rápido',
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
                'icon'  => 'fa-chart-bar',
                'color' => 'danger',
                'img'   => 'reportes.png',
                'desc'  => 'Informes y estadísticas',
            ],
            [
                'label' => 'Mantenimiento',
                'route' => 'mantenimiento.index',
                'icon'  => 'fa-screwdriver-wrench',
                'color' => 'secondary',
                'img'   => 'mantencion.png',
                'desc'  => 'Soporte técnico y mantenciones',
            ],
            [
                'label' => 'Configuración',
                'route' => 'configuracion.index',
                'icon'  => 'fa-sliders',
                'color' => 'dark',
                'img'   => 'ajustes.png',
                'desc'  => 'Parámetros del sistema',
            ],
        ];
    @endphp

   <div class="row g-4">
    @foreach($modulos as $index => $m)

        {{-- INSERTAR BLOQUES DESPUÉS DE LA 4TA CARD --}}
        @if($index == 4)

            {{-- DEMOS MED-SDI --}}
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 mb-4">
                <div class="demo-box demo-blue text-white p-3  shadow-sm">
                    <h5 class="fw-bold text-center mb-3">DEMOS MED-SDI</h5>

                    <div class="row g-3">
    @foreach($medDemos as $item)
        <div class="col-6 col-md-3">
            <a href="{{ route($item['route']) }}" class="btn demo-btn w-100">
                {{ $item['label'] }}
            </a>
        </div>
    @endforeach
</div>
                </div>
            </div>

            {{-- DEMOS VET-SDI --}}
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 mb-4">
                <div class="demo-box demo-purple text-white p-3  shadow-sm">
                    <h5 class="fw-bold text-center mb-3">DEMOS VET-SDI</h5>

                    <div class="row g-3">
    @foreach($vetDemos as $item)
        <div class="col-6 col-md-3">
            <a href="{{ route($item['route']) }}" class="btn demo-btn w-100">
                {{ $item['label'] }}
            </a>
        </div>
    @endforeach
</div>
                </div>
            </div>

        @endif

        {{-- TUS CARDS (NO SE TOCAN) --}}
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

    .demo-box {
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
    border-radius: 0.85rem !important;
}

.demo-blue {
    background: #6d92d1;
}

.demo-purple {
    background: #ba98c8;
}

.demo-btn {
    background: rgba(255,255,255,0.15);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    padding: 10px;
    transition: all .2s ease;
    font-size:0.9rem;
    margin-bottom: 7px;
}

.demo-btn:hover {
    background: rgba(255,255,255,0.30);
    transform: translateY(-2px);
    color: #fff;

}
</style>
@endpush
