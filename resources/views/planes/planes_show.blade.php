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
@section("titulo", "Detalle del Plan")
@section("contenido")
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Detalle del Plan <i class="fa fa-info-circle"></i></h1>

            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{$plan->nombre}}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Información General</h5>
                            <p><strong>Descripción:</strong></p>
                            <p>{{$plan->descripcion}}</p>

                            <p><strong>Precio:</strong></p>
                            <h3 class="text-success">${{number_format($plan->precio, 2)}}</h3>

                            <p><strong>Frecuencia:</strong></p>
                            <p>
                                <span class="badge bg-info">{{ucfirst($plan->frecuencia)}}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Características Técnicas</h5>
                            <p><strong>Cantidad de Usuarios:</strong></p>
                            <p><span class="badge bg-primary">{{$plan->cantidad_usuarios}}</span></p>

                            <p><strong>Cantidad de Dispositivos:</strong></p>
                            <p><span class="badge bg-primary">{{$plan->cantidad_dispositivos}}</span></p>

                            <p><strong>Estado:</strong></p>
                            <p>
                                @if($plan->estado)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if(is_array($plan->features) && count($plan->features) > 0)
                        <hr>
                        <h5>Características Incluidas:</h5>
                        <ul class="list-group">
                            @foreach($plan->features as $feature)
                                <li class="list-group-item">
                                    <i class="fa fa-check text-success"></i> {{$feature}}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <hr>
                    <div class="row">
                        <strong>Fecha de Creación:</strong> {{$plan->created_at->format('d/m/Y H:i')}}
                    </div>
                    <div class="row mt-2">
                        <strong>Última Actualización:</strong> {{$plan->updated_at->format('d/m/Y H:i')}}
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{route("planes.edit", $plan)}}" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Editar
                    </a>
                    <a href="{{route("planes.index")}}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
