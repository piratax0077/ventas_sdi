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
@section("titulo", "Crear Reporte")
@section("contenido")
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fa fa-plus-circle"></i> Crear Nuevo Reporte</h1>
                <a href="{{route("reportes.index")}}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Volver
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="fa fa-exclamation-circle"></i> Errores encontrados</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{route("reportes.store")}}" method="post" class="card shadow-sm">
                @csrf

                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información del Reporte</h5>
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
                               value="{{old('nombre')}}"
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
                                  placeholder="Describa el propósito de este reporte">{{old('descripcion')}}</textarea>
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
                                <option value="mensual" {{old('tipo') === 'mensual' ? 'selected' : ''}}>Mensual</option>
                                <option value="trimestral" {{old('tipo') === 'trimestral' ? 'selected' : ''}}>Trimestral</option>
                                <option value="anual" {{old('tipo') === 'anual' ? 'selected' : ''}}>Anual</option>
                                <option value="personalizado" {{old('tipo') === 'personalizado' ? 'selected' : ''}}>Personalizado</option>
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
                                <option value="diaria" {{old('frecuencia') === 'diaria' ? 'selected' : ''}}>Diaria</option>
                                <option value="semanal" {{old('frecuencia') === 'semanal' ? 'selected' : ''}}>Semanal</option>
                                <option value="mensual" {{old('frecuencia') === 'mensual' ? 'selected' : ''}}>Mensual</option>
                                <option value="trimestral" {{old('frecuencia') === 'trimestral' ? 'selected' : ''}}>Trimestral</option>
                                <option value="anual" {{old('frecuencia') === 'anual' ? 'selected' : ''}}>Anual</option>
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
                                  placeholder="Ingrese emails separados por comas&#10;ej: admin@empresa.com, gerente@empresa.com, director@empresa.com">{{old('destinatarios')}}</textarea>
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
                                  placeholder="Ingrese el contenido HTML del reporte. Puede usar variables como @{{nombre}}, @{{fecha}}, etc.">{{old('contenido')}}</textarea>
                        <small class="form-text text-muted">Puede incluir HTML. Use variables dinámicas entre llaves: @{{fecha}}, @{{periodo}}</small>
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
                                   {{old('estado') ? 'checked' : ''}}>
                            <label class="form-check-label" for="estado">
                                Activo
                            </label>
                        </div>
                        <small class="form-text text-muted">Marque para activar este reporte (solo reportes activos se ejecutan)</small>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Guardar Reporte
                        </button>
                        <a href="{{route("reportes.index")}}" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar de Ayuda -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fa fa-lightbulb"></i> Ayuda y Guía</h5>
                </div>
                <div class="card-body small">
                    <h6 class="fw-bold mb-2">Tipos de Reporte</h6>
                    <ul class="small ps-3 mb-3">
                        <li><strong>Mensual:</strong> Reportes mensuales</li>
                        <li><strong>Trimestral:</strong> Reportes cada 3 meses</li>
                        <li><strong>Anual:</strong> Reportes anuales</li>
                        <li><strong>Personalizado:</strong> Según lo defina</li>
                    </ul>

                    <hr>

                    <h6 class="fw-bold mb-2">Frecuencias de Ejecución</h6>
                    <ul class="small ps-3 mb-3">
                        <li><strong>Diaria:</strong> Se ejecuta todos los días</li>
                        <li><strong>Semanal:</strong> Se ejecuta cada semana</li>
                        <li><strong>Mensual:</strong> Se ejecuta cada mes</li>
                        <li><strong>Trimestral:</strong> Se ejecuta cada 3 meses</li>
                        <li><strong>Anual:</strong> Se ejecuta anualmente</li>
                    </ul>

                    <hr>

                    <h6 class="fw-bold mb-2">Variables Disponibles</h6>
                    <div class="small ps-3">
                        <code class="bg-light p-1 rounded d-block my-1">{{'{'}}fecha{{'}'}} </code>
                        <span class="text-muted">Fecha actual</span>

                        <code class="bg-light p-1 rounded d-block my-1 mt-2">{{'{'}}periodo{{'}'}} </code>
                        <span class="text-muted">Periodo del reporte</span>

                        <code class="bg-light p-1 rounded d-block my-1 mt-2">{{'{'}}empresa{{'}'}} </code>
                        <span class="text-muted">Nombre empresa</span>

                        <code class="bg-light p-1 rounded d-block my-1 mt-2">{{'{'}}usuario{{'}'}} </code>
                        <span class="text-muted">Nombre usuario actual</span>
                    </div>

                    <hr>

                    <div class="alert alert-info small mb-0">
                        <i class="fa fa-info-circle"></i>
                        <strong>Tip:</strong> Solo los reportes activos se ejecutarán automáticamente según la frecuencia configurada.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
