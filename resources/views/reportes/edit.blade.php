{{--

  ____          _____               _ _           _
 |  _ \        |  __ \             (_) |         | |
 | |_) |_   _  | |__) |_ _ _ __ _____| |__  _   _| |_ ___
 |  _ <| | | | |  ___/ _` | '__|_  / | '_ \| | | | __/ _ \
 | |_) | |_| | | |  | (_| | |   / /| | |_) | |_| | ||  __/
 |____/ \__, | |_|   \__,_|_|  /___|_|_.__/ \__, |\__\___|
         __/ |                               __/ |
        |___|                               |___/

    Blog:       https://parzibyte.me/blog
    Ayuda:      https://parzibyte.me/blog/contrataciones-ayuda/
    Contacto:   https://parzibyte.me/blog/contacto/

    Copyright (c) 2020 Luis Cabrera Benito
    Licenciado bajo la licencia MIT

    El texto de arriba debe ser incluido en cualquier redistribucion
--}}
@extends("maestra")
@section("titulo", "Editar Reporte")
@section("contenido")
    <div class="row">
        <div class="col-lg-8">
            <h1>Editar Reporte <i class="fa fa-edit"></i></h1>

            <a href="{{route("reportes.index")}}" class="btn btn-secondary mb-3">
                <i class="fa fa-arrow-left"></i> Volver
            </a>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{route("reportes.update", $reporte)}}" method="post" class="card">
                @csrf
                @method("put")

                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">Actualizar Reporte: <strong>{{$reporte->nombre}}</strong></h5>
                </div>

                <div class="card-body">
                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre"
                               name="nombre"
                               placeholder="ej: Reporte de Ventas Mensuales"
                               value="{{old('nombre', $reporte->nombre)}}"
                               required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                  id="descripcion"
                                  name="descripcion"
                                  rows="3"
                                  placeholder="Describa el propósito de este reporte">{{old('descripcion', $reporte->descripcion)}}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Tipo -->
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                            <select class="form-select @error('tipo') is-invalid @enderror"
                                    id="tipo"
                                    name="tipo"
                                    required>
                                <option value="">-- Seleccionar tipo --</option>
                                <option value="mensual" {{$reporte->tipo === 'mensual' ? 'selected' : ''}}>Mensual</option>
                                <option value="trimestral" {{$reporte->tipo === 'trimestral' ? 'selected' : ''}}>Trimestral</option>
                                <option value="anual" {{$reporte->tipo === 'anual' ? 'selected' : ''}}>Anual</option>
                                <option value="personalizado" {{$reporte->tipo === 'personalizado' ? 'selected' : ''}}>Personalizado</option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Frecuencia -->
                        <div class="col-md-6 mb-3">
                            <label for="frecuencia" class="form-label">Frecuencia <span class="text-danger">*</span></label>
                            <select class="form-select @error('frecuencia') is-invalid @enderror"
                                    id="frecuencia"
                                    name="frecuencia"
                                    required>
                                <option value="">-- Seleccionar frecuencia --</option>
                                <option value="diaria" {{$reporte->frecuencia === 'diaria' ? 'selected' : ''}}>Diaria</option>
                                <option value="semanal" {{$reporte->frecuencia === 'semanal' ? 'selected' : ''}}>Semanal</option>
                                <option value="mensual" {{$reporte->frecuencia === 'mensual' ? 'selected' : ''}}>Mensual</option>
                                <option value="trimestral" {{$reporte->frecuencia === 'trimestral' ? 'selected' : ''}}>Trimestral</option>
                                <option value="anual" {{$reporte->frecuencia === 'anual' ? 'selected' : ''}}>Anual</option>
                            </select>
                            @error('frecuencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Destinatarios -->
                    <div class="mb-3">
                        <label for="destinatarios" class="form-label">Destinatarios</label>
                        <textarea class="form-control @error('destinatarios') is-invalid @enderror"
                                  id="destinatarios"
                                  name="destinatarios"
                                  rows="3"
                                  placeholder="Ingrese emails separados por comas">{{old('destinatarios', $reporte->destinatarios)}}</textarea>
                        <small class="form-text text-muted">Emails separados por coma (,)</small>
                        @error('destinatarios')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contenido -->
                    <div class="mb-3">
                        <label for="contenido" class="form-label">Contenido del Reporte (HTML)</label>
                        <textarea class="form-control @error('contenido') is-invalid @enderror"
                                  id="contenido"
                                  name="contenido"
                                  rows="6"
                                  placeholder="Ingrese el contenido HTML del reporte">{{old('contenido', $reporte->contenido)}}</textarea>
                        <small class="form-text text-muted">Puede incluir HTML. Use variables dinámicas entre llaves</small>
                        @error('contenido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="estado"
                                   name="estado"
                                   value="1"
                                   {{$reporte->estado ? 'checked' : ''}}>
                            <label class="form-check-label" for="estado">
                                Activo
                            </label>
                        </div>
                        <small class="form-text text-muted">Marque para activar este reporte</small>
                    </div>

                    <!-- Información de auditoría -->
                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="fa fa-info-circle"></i>
                            Creado: {{$reporte->created_at->format('d/m/Y H:i')}}<br>
                            Última modificación: {{$reporte->updated_at->format('d/m/Y H:i')}}
                            @if($reporte->ultima_ejecucion)
                                <br>Última ejecución: {{$reporte->ultima_ejecucion->format('d/m/Y H:i')}}
                            @endif
                            @if($reporte->proxima_ejecucion)
                                <br>Próxima ejecución: {{$reporte->proxima_ejecucion->format('d/m/Y H:i')}}
                            @endif
                        </small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Actualizar Reporte
                    </button>
                    <a href="{{route("reportes.index")}}" class="btn btn-secondary">
                        <i class="fa fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Información</h5>
                </div>
                <div class="card-body">
                    <h6>Detalles Actuales</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <small class="text-muted">Tipo</small><br>
                            <strong>{{ucfirst($reporte->tipo)}}</strong>
                        </div>
                        <div class="list-group-item">
                            <small class="text-muted">Frecuencia</small><br>
                            <span class="badge bg-secondary">{{ucfirst($reporte->frecuencia)}}</span>
                        </div>
                        <div class="list-group-item">
                            <small class="text-muted">Destinatarios</small><br>
                            {{count($reporte->obtenerDestinatarios())}} email(s)
                        </div>
                        <div class="list-group-item">
                            <small class="text-muted">Estado</small><br>
                            @if($reporte->estado)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <h6>Acciones</h6>
                    <a href="{{route("reportes.show", $reporte)}}" class="btn btn-sm btn-info w-100 mb-2">
                        <i class="fa fa-eye"></i> Ver Detalles
                    </a>
                    <form action="{{route("reportes.destroy", $reporte)}}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('¿Está seguro?')">
                            <i class="fa fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
