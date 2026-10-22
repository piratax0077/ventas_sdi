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
@section("titulo", "Detalles de Configuración")
@section("contenido")
    <div class="row">
        <div class="col-lg-8">
            <h1>Detalles de Configuración <i class="fa fa-details"></i></h1>

            <a href="{{route("configuracion.index")}}" class="btn btn-secondary mb-3">
                <i class="fa fa-arrow-left"></i> Volver
            </a>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <code>{{$configuracion->clave}}</code>
                        @if($configuracion->estado)
                            <span class="badge bg-success float-end">Activo</span>
                        @else
                            <span class="badge bg-danger float-end">Inactivo</span>
                        @endif
                    </h5>
                </div>

                <div class="card-body">
                    <!-- Clave -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Clave</label>
                            <p class="lead">
                                <code>{{$configuracion->clave}}</code>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tipo</label>
                            <p>
                                <span class="badge bg-info" style="font-size: 0.9rem;">
                                    {{ucfirst($configuracion->tipo)}}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- Valor -->
                    <div class="mb-3">
                        <label class="form-label text-muted">Valor</label>
                        @if($configuracion->tipo === 'textarea')
                            <div class="bg-light p-3 rounded border">
                                <pre>{{$configuracion->valor}}</pre>
                            </div>
                        @elseif($configuracion->tipo === 'boolean')
                            <p>
                                @if($configuracion->valor === '1' || $configuracion->valor === 1 || $configuracion->valor === true)
                                    <span class="badge bg-success" style="font-size: 0.9rem;">Sí</span>
                                @else
                                    <span class="badge bg-danger" style="font-size: 0.9rem;">No</span>
                                @endif
                            </p>
                        @elseif($configuracion->tipo === 'url')
                            <p>
                                <a href="{{$configuracion->valor}}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fa fa-external-link-alt"></i> {{$configuracion->valor}}
                                </a>
                            </p>
                        @elseif($configuracion->tipo === 'email')
                            <p>
                                <a href="mailto:{{$configuracion->valor}}" class="btn btn-sm btn-info">
                                    <i class="fa fa-envelope"></i> {{$configuracion->valor}}
                                </a>
                            </p>
                        @else
                            <p class="lead">{{$configuracion->valor ?? '(vacío)'}}</p>
                        @endif
                    </p>
                    </div>

                    <hr>

                    <!-- Categoría -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Categoría</label>
                            <p>
                                <span class="badge bg-secondary" style="font-size: 0.9rem;">
                                    {{ucfirst($configuracion->categoria)}}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Estado</label>
                            <p>
                                @if($configuracion->estado)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label class="form-label text-muted">Descripción</label>
                        @if($configuracion->descripcion)
                            <p>{{$configuracion->descripcion}}</p>
                        @else
                            <p class="text-muted">(Sin descripción)</p>
                        @endif
                    </div>

                    <hr>

                    <!-- Información de Auditoría -->
                    <div class="alert alert-info">
                        <small>
                            <i class="fa fa-calendar-alt"></i>
                            <strong>Creado:</strong> {{$configuracion->created_at->format('d/m/Y H:i:s')}}<br>
                            <strong>Última modificación:</strong> {{$configuracion->updated_at->format('d/m/Y H:i:s')}}
                        </small>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{route("configuracion.edit", $configuracion)}}" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Editar
                    </a>
                    <form action="{{route("configuracion.destroy", $configuracion)}}" method="post" style="display: inline;">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar esta configuración?')">
                            <i class="fa fa-trash"></i> Eliminar
                        </button>
                    </form>
                    <a href="{{route("configuracion.index")}}" class="btn btn-secondary">
                        <i class="fa fa-list"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Card de información complementaria -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <small class="text-muted">Identificador</small><br>
                            <code>{{$configuracion->id}}</code>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Clave</small><br>
                            <code style="word-break: break-all;">{{$configuracion->clave}}</code>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Tipo de Valor</small><br>
                            <span class="badge bg-info">{{ucfirst($configuracion->tipo)}}</span>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Categoría</small><br>
                            <span class="badge bg-secondary">{{ucfirst($configuracion->categoria)}}</span>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Estado</small><br>
                            @if($configuracion->estado)
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
                        <a href="{{route("configuracion.edit", $configuracion)}}" class="btn btn-warning">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                        <a href="{{route("configuracion.index")}}" class="btn btn-info">
                            <i class="fa fa-list"></i> Listado Completo
                        </a>
                        <a href="{{route("configuracion.create")}}" class="btn btn-success">
                            <i class="fa fa-plus"></i> Agregar Nueva
                        </a>
                        <form action="{{route("configuracion.destroy", $configuracion)}}" method="post">
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
