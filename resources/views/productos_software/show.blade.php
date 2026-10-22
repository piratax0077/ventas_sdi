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
@section("titulo", "Detalle del Producto Software")
@section("contenido")
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Detalle del Producto Software <i class="fa fa-info-circle"></i></h1>

            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{$productoSoftware->nombre}}</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Descripción</h5>
                        <p>{{$productoSoftware->descripcion}}</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Información Técnica</h5>
                            <p>
                                <strong>Versión:</strong> {{$productoSoftware->versión ?? 'No especificada'}}
                            </p>
                            <p>
                                <strong>Tipo:</strong>
                                <span class="badge bg-info">{{ucfirst($productoSoftware->tipo)}}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Estado</h5>
                            <p>
                                @if($productoSoftware->estado)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </p>
                            @if($productoSoftware->url_descarga)
                                <p>
                                    <strong>Descarga:</strong>
                                    <a href="{{$productoSoftware->url_descarga}}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fa fa-download"></i> Descargar
                                    </a>
                                </p>
                            @endif
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <span class="text-muted">
                            <strong>Creado:</strong> {{$productoSoftware->created_at->format('d/m/Y H:i')}}
                        </span>
                    </div>
                    <div class="row mt-2">
                        <span class="text-muted">
                            <strong>Actualizado:</strong> {{$productoSoftware->updated_at->format('d/m/Y H:i')}}
                        </span>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{route("productos-software.edit", $productoSoftware)}}" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Editar
                    </a>
                    <a href="{{route("productos-software.index")}}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
