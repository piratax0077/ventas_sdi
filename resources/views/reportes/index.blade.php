{{--

  ____          _____               _ _           _
 |  _ \        |  __ \             (_) |         | |
 | |_) |_   _  | |__) |_ _ _ __ _____| |__  _   _| |_ ___
 |  _ <| | | | |  ___/ _` | '__|_  / | '_ \| | | | __/ _ \
 | |_) | |_| | | |  | (_| | |   / /| | |_) | |_| | ||  __/
 |____/ \__, | |_|   \__,_|_|  /___|_|_.__/ \__, |\__\___|
         __/ |                               __/ |
        |___/                               |___/

    Blog:       https://parzibyte.me/blog
    Ayuda:      https://parzibyte.me/blog/contrataciones-ayuda/
    Contacto:   https://parzibyte.me/blog/contacto/

    Copyright (c) 2020 Luis Cabrera Benito
    Licenciado bajo la licencia MIT

    El texto de arriba debe ser incluido en cualquier redistribucion
--}}
@extends("maestra")
@section("titulo", "Reportes")
@section("contenido")
    <div class="row">
        <div class="col-12">
            <h1>Reportes del Sistema <i class="fa fa-file-alt"></i></h1>
            <a href="{{route("reportes.create")}}" class="btn btn-success mb-2">
                <i class="fa fa-plus"></i> Crear Nuevo Reporte
            </a>
            @include("notificacion")

            <!-- Filtro por Tipo -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a href="{{route("reportes.index")}}" class="btn btn-outline-secondary">
                            <i class="fa fa-list"></i> Todos
                        </a>
                        @foreach($tipos as $tipo)
                            <a href="?tipo={{$tipo}}" class="btn btn-outline-primary">
                                {{ucfirst($tipo)}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tabla de Reportes -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Frecuencia</th>
                        <th>Destinatarios</th>
                        <th>Última Ejecución</th>
                        <th>Próxima Ejecución</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $filtroTipo = request('tipo');
                        $reportesFiltrados = $filtroTipo
                            ? $reportes->where('tipo', $filtroTipo)
                            : $reportes;
                    @endphp

                    @forelse($reportesFiltrados as $reporte)
                        <tr>
                            <td>
                                <strong>{{$reporte->nombre}}</strong>
                            </td>
                            <td>
                                @if($reporte->descripcion)
                                    <small class="text-muted">{{Str::limit($reporte->descripcion, 50)}}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ucfirst($reporte->tipo)}}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ucfirst($reporte->frecuencia)}}</span>
                            </td>
                            <td>
                                @if($reporte->destinatarios)
                                    <small>
                                        <i class="fa fa-envelope"></i>
                                        {{count($reporte->obtenerDestinatarios())}} {{count($reporte->obtenerDestinatarios()) === 1 ? 'destinatario' : 'destinatarios'}}
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($reporte->ultima_ejecucion)
                                    <small class="text-muted">{{$reporte->ultima_ejecucion->format('d/m/Y H:i')}}</small>
                                @else
                                    <span class="badge bg-warning">Nunca ejecutado</span>
                                @endif
                            </td>
                            <td>
                                @if($reporte->proxima_ejecucion)
                                    <small class="text-muted">{{$reporte->proxima_ejecucion->format('d/m/Y H:i')}}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($reporte->estado)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td style="white-space: nowrap;">
                                <a class="btn btn-info btn-sm" href="{{route("reportes.show", $reporte)}}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a class="btn btn-warning btn-sm" href="{{route("reportes.edit", $reporte)}}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{route("reportes.destroy", $reporte)}}" method="post" style="display:inline">
                                    @method("delete")
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No hay reportes registrados. <a href="{{route("reportes.create")}}">Crear uno ahora</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
