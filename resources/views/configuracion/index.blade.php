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
@section("titulo", "Configuraciones del Sistema")
@section("contenido")
    <div class="row">
        <div class="col-12">
            <h1>Configuraciones del Sistema <i class="fa fa-cog"></i></h1>
            <a href="{{route("configuracion.create")}}" class="btn btn-success mb-2">
                <i class="fa fa-plus"></i> Agregar Configuración
            </a>
            @include("notificacion")

            <!-- Filtro por Categoría -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a href="{{route("configuracion.index")}}" class="btn btn-outline-secondary">
                            <i class="fa fa-list"></i> Todas
                        </a>
                        @foreach($categorias as $cat)
                            <a href="?categoria={{$cat}}" class="btn btn-outline-primary">
                                {{ucfirst($cat)}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tabla de Configuraciones -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>Clave</th>
                        <th>Valor</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Ver</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $filtroCategoria = request('categoria');
                        $configFiltradas = $filtroCategoria
                            ? $configuraciones->where('categoria', $filtroCategoria)
                            : $configuraciones;
                    @endphp

                    @forelse($configFiltradas as $config)
                        <tr>
                            <td>
                                <code>{{$config->clave}}</code>
                            </td>
                            <td>
                                @if($config->tipo === 'textarea')
                                    <small class="text-muted">{{Str::limit($config->valor, 50)}}</small>
                                @elseif($config->tipo === 'boolean')
                                    @if($config->valor === '1' || $config->valor === 1 || $config->valor === true)
                                        <span class="badge bg-success">Sí</span>
                                    @else
                                        <span class="badge bg-danger">No</span>
                                    @endif
                                @elseif($config->tipo === 'url')
                                    <a href="{{$config->valor}}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fa fa-link"></i> Ir
                                    </a>
                                @else
                                    <small>{{Str::limit($config->valor, 40)}}</small>
                                @endif
                            </td>
                            <td>
                                @if($config->descripcion)
                                    <small class="text-muted">{{Str::limit($config->descripcion, 50)}}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ucfirst($config->tipo)}}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ucfirst($config->categoria)}}</span>
                            </td>
                            <td>
                                @if($config->estado)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-info btn-sm" href="{{route("configuracion.show", $config)}}">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-warning btn-sm" href="{{route("configuracion.edit", $config)}}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td>
                                <form action="{{route("configuracion.destroy", $config)}}" method="post" style="display:inline">
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
                                No hay configuraciones registradas. <a href="{{route("configuracion.create")}}">Crear una ahora</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
