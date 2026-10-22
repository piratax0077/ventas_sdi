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
@section("titulo", "Crear Producto Software")
@section("contenido")
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Crear Nuevo Producto Software <i class="fa fa-plus"></i></h1>
            @include("notificacion")

            <form action="{{route("productos-software.store")}}" method="post" class="mt-4">
                @csrf

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Información del Producto</h5>
                    </div>
                    <div class="card-body">
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                id="nombre" name="nombre" value="{{old('nombre')}}"
                                placeholder="Ej: CRM Manager Pro" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                id="descripcion" name="descripcion" rows="4"
                                placeholder="Describe qué hace este producto" required>{{old('descripcion')}}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Versión -->
                        <div class="mb-3">
                            <label for="versión" class="form-label">Versión</label>
                            <input type="text" class="form-control @error('versión') is-invalid @enderror"
                                id="versión" name="versión" value="{{old('versión')}}"
                                placeholder="Ej: 2.5.1">
                            @error('versión')
                                <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Tipo de Software -->
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Software <span class="text-danger">*</span></label>
                            <select class="form-select @error('tipo') is-invalid @enderror"
                                id="tipo" name="tipo" required>
                                <option value="">-- Seleccionar --</option>
                                <option value="web" {{old('tipo') == 'web' ? 'selected' : ''}}>Aplicación Web</option>
                                <option value="desktop" {{old('tipo') == 'desktop' ? 'selected' : ''}}>Desktop</option>
                                <option value="mobile" {{old('tipo') == 'mobile' ? 'selected' : ''}}>App Móvil</option>
                                <option value="api" {{old('tipo') == 'api' ? 'selected' : ''}}>API/Servicio</option>
                                <option value="otro" {{old('tipo') == 'otro' ? 'selected' : ''}}>Otro</option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback" style="display:block">{{$message}}</div>
                            @enderror
                        </div>

                        <!-- URL Descarga -->
                        <div class="mb-3">
                            <label for="url_descarga" class="form-label">URL de Descarga</label>
                            <input type="url" class="form-control @error('url_descarga') is-invalid @enderror"
                                id="url_descarga" name="url_descarga" value="{{old('url_descarga')}}"
                                placeholder="https://ejemplo.com/descargar">
                            @error('url_descarga')
                                <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="estado" name="estado" value="1"
                                {{old('estado') ? 'checked' : 'checked'}}>
                            <label class="form-check-label" for="estado">
                                Producto Activo
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="mt-4 mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save"></i> Guardar Producto
                    </button>
                    <a href="{{route("productos-software.index")}}" class="btn btn-secondary btn-lg">
                        <i class="fa fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
