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
@section("titulo", "Detalles del Reporte")
@section("contenido")
    <div class="row">
        <div class="col-lg-8">
            <h1>Detalles del Reporte <i class="fa fa-file-alt"></i></h1>

            <a href="{{route("reportes.index")}}" class="btn btn-secondary mb-3">
                <i class="fa fa-arrow-left"></i> Volver
            </a>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        {{$reporte->nombre}}
                        @if($reporte->estado)
                            <span class="badge bg-success float-end">Activo</span>
                        @else
                            <span class="badge bg-danger float-end">Inactivo</span>
                        @endif
                    </h5>
                </div>

                <div class="card-body">
                    <!-- Descripción -->
                    <div class="mb-3">
                        <label class="form-label text-muted">Descripción</label>
                        @if($reporte->descripcion)
                            <p>{{$reporte->descripcion}}</p>
                        @else
                            <p class="text-muted">(Sin descripción)</p>
                        @endif
                    </div>

                    <hr>

                    <!-- Tipo y Frecuencia -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tipo</label>
                            <p>
                                <span class="badge bg-info" style="font-size: 0.9rem;">
                                    {{ucfirst($reporte->tipo)}}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Frecuencia</label>
                            <p>
                                <span class="badge bg-secondary" style="font-size: 0.9rem;">
                                    {{ucfirst($reporte->frecuencia)}}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- Destinatarios -->
                    <div class="mb-3">
                        <label class="form-label text-muted">Destinatarios</label>
                        @if($reporte->destinatarios)
                            <div class="bg-light p-3 rounded border">
                                <ul class="mb-0">
                                    @foreach($reporte->obtenerDestinatarios() as $email)
                                        <li>
                                            <a href="mailto:{{$email}}">
                                                <i class="fa fa-envelope"></i> {{$email}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="text-muted">(Sin destinatarios configurados)</p>
                        @endif
                    </div>

                    <hr>

                    <!-- Contenido -->
                    <div class="mb-3">
                        <label class="form-label text-muted">Contenido del Reporte</label>
                        @if($reporte->contenido)
                            <div class="bg-light p-3 rounded border">
                                <pre style="white-space: pre-wrap; word-wrap: break-word;">{{$reporte->contenido}}</pre>
                            </div>
                        @else
                            <p class="text-muted">(Sin contenido)</p>
                        @endif
                    </div>

                    <hr>

                    <!-- Información de Ejecución -->
                    <div class="alert alert-info">
                        <small>
                            <i class="fa fa-history"></i>
                            <strong>Creado:</strong> {{$reporte->created_at->format('d/m/Y H:i:s')}}<br>
                            <strong>Última modificación:</strong> {{$reporte->updated_at->format('d/m/Y H:i:s')}}
                            @if($reporte->ultima_ejecucion)
                                <br><strong>Última ejecución:</strong> {{$reporte->ultima_ejecucion->format('d/m/Y H:i:s')}}
                            @else
                                <br><strong>Última ejecución:</strong> <span class="badge bg-warning">Nunca ejecutado</span>
                            @endif
                            @if($reporte->proxima_ejecucion)
                                <br><strong>Próxima ejecución:</strong> {{$reporte->proxima_ejecucion->format('d/m/Y H:i:s')}}
                            @else
                                <br><strong>Próxima ejecución:</strong> <span class="text-muted">Por definir</span>
                            @endif
                        </small>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{route("reportes.edit", $reporte)}}" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Editar
                    </a>
                    <form action="{{route("reportes.destroy", $reporte)}}" method="post" style="display: inline;">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar este reporte?')">
                            <i class="fa fa-trash"></i> Eliminar
                        </button>
                    </form>
                    <a href="{{route("reportes.index")}}" class="btn btn-secondary">
                        <i class="fa fa-list"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Card de información complementaria -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Estado del Reporte</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <small class="text-muted">Identificador</small><br>
                            <code>{{$reporte->id}}</code>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Tipo</small><br>
                            <span class="badge bg-info">{{ucfirst($reporte->tipo)}}</span>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Frecuencia</small><br>
                            <span class="badge bg-secondary">{{ucfirst($reporte->frecuencia)}}</span>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Destinatarios Totales</small><br>
                            <strong>{{count($reporte->obtenerDestinatarios())}}</strong>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Estado</small><br>
                            @if($reporte->estado)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de acciones -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{route("reportes.edit", $reporte)}}" class="btn btn-warning">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                        <a href="{{route("reportes.index")}}" class="btn btn-info">
                            <i class="fa fa-list"></i> Listado Completo
                        </a>
                        <a href="{{route("reportes.create")}}" class="btn btn-success">
                            <i class="fa fa-plus"></i> Crear Nuevo
                        </a>
                        <form action="{{route("reportes.destroy", $reporte)}}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('¿Está seguro?')">
                                <i class="fa fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
