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
@section("titulo", "Nuevo Plan de Software")
@section("contenido")
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Crear Nuevo Plan <i class="fa fa-plus"></i></h1>
            @include("notificacion")

            <form action="{{route("planes.store")}}" method="post" class="mt-4">
                @csrf

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Información General del Plan</h5>
                    </div>
                    <div class="card-body">
                        <!-- Nombre del Plan -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Plan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                id="nombre" name="nombre" value="{{old('nombre')}}"
                                placeholder="Ej: Plan Professional" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                id="descripcion" name="descripcion" rows="3"
                                placeholder="Describe las características principales del plan" required>{{old('descripcion')}}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Precio y Frecuencia -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio" class="form-label">Precio <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('precio') is-invalid @enderror"
                                            id="precio" name="precio" value="{{old('precio')}}"
                                            placeholder="0.00" step="0.01" required>
                                    </div>
                                    @error('precio')
                                        <div class="invalid-feedback" style="display:block">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="frecuencia" class="form-label">Frecuencia <span class="text-danger">*</span></label>
                                    <select class="form-select @error('frecuencia') is-invalid @enderror"
                                        id="frecuencia" name="frecuencia" required>
                                        <option value="">-- Seleccionar --</option>
                                        <option value="mensual" {{old('frecuencia') == 'mensual' ? 'selected' : ''}}>Mensual</option>
                                        <option value="trimestral" {{old('frecuencia') == 'trimestral' ? 'selected' : ''}}>Trimestral</option>
                                        <option value="semestral" {{old('frecuencia') == 'semestral' ? 'selected' : ''}}>Semestral</option>
                                        <option value="anual" {{old('frecuencia') == 'anual' ? 'selected' : ''}}>Anual</option>
                                    </select>
                                    @error('frecuencia')
                                        <div class="invalid-feedback" style="display:block">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Características del Plan -->
                <div class="card mt-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Características del Plan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cantidad_usuarios" class="form-label">Cantidad de Usuarios <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('cantidad_usuarios') is-invalid @enderror"
                                        id="cantidad_usuarios" name="cantidad_usuarios" value="{{old('cantidad_usuarios')}}"
                                        placeholder="1" min="1" required>
                                    @error('cantidad_usuarios')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cantidad_dispositivos" class="form-label">Cantidad de Dispositivos <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('cantidad_dispositivos') is-invalid @enderror"
                                        id="cantidad_dispositivos" name="cantidad_dispositivos" value="{{old('cantidad_dispositivos')}}"
                                        placeholder="1" min="1" required>
                                    @error('cantidad_dispositivos')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="mb-3">
                            <label for="features" class="form-label">Features/Características <span class="text-danger">*</span></label>
                            <small class="d-block text-muted mb-2">Ingresa cada característica en una línea diferente</small>
                            <textarea class="form-control @error('features') is-invalid @enderror"
                                id="features" name="features" rows="5"
                                placeholder="Soporte 24/7&#10;Almacenamiento ilimitado&#10;API integrada&#10;Reportes avanzados&#10;Seguridad SSL">{{old('features')}}</textarea>
                            @error('features')
                                <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Estado del Plan</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="estado" name="estado" value="1"
                                {{old('estado') ? 'checked' : 'checked'}}>
                            <label class="form-check-label" for="estado">
                                Plan Activo
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="mt-4 mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save"></i> Guardar Plan
                    </button>
                    <a href="{{route("planes.index")}}" class="btn btn-secondary btn-lg">
                        <i class="fa fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
