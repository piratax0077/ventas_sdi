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
@section("titulo", "Agregar Configuración")
@section("contenido")
    <div class="row">
        <div class="col-lg-8">
            <h1>Agregar Nueva Configuración <i class="fa fa-plus-circle"></i></h1>

            <a href="{{route("configuracion.index")}}" class="btn btn-secondary mb-3">
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

            <form action="{{route("configuracion.store")}}" method="post" class="card">
                @csrf

                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información de Configuración</h5>
                </div>

                <div class="card-body">
                    <!-- Clave -->
                    <div class="mb-3">
                        <label for="clave" class="form-label">Clave <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('clave') is-invalid @enderror"
                               id="clave"
                               name="clave"
                               placeholder="ej: NOMBRE_EMPRESA"
                               value="{{old('clave')}}"
                               required>
                        @error('clave')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Identificador único de la configuración (sin espacios)</small>
                    </div>

                    <!-- Tipo -->
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo') is-invalid @enderror"
                                id="tipo"
                                name="tipo"
                                onchange="actualizarCampoValor()"
                                required>
                            <option value="">-- Seleccionar tipo --</option>
                            <option value="text" {{old('tipo') === 'text' ? 'selected' : ''}}>Texto</option>
                            <option value="email" {{old('tipo') === 'email' ? 'selected' : ''}}>Email</option>
                            <option value="url" {{old('tipo') === 'url' ? 'selected' : ''}}>URL</option>
                            <option value="number" {{old('tipo') === 'number' ? 'selected' : ''}}>Número</option>
                            <option value="textarea" {{old('tipo') === 'textarea' ? 'selected' : ''}}>Texto Largo</option>
                            <option value="boolean" {{old('tipo') === 'boolean' ? 'selected' : ''}}>Sí/No</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Valor -->
                    <div class="mb-3" id="contenedorValor">
                        <label for="valor" class="form-label">Valor</label>
                        <input type="text"
                               class="form-control @error('valor') is-invalid @enderror"
                               id="valor"
                               name="valor"
                               placeholder="Ingrese el valor"
                               value="{{old('valor')}}">
                        @error('valor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Categoría -->
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
                        <select class="form-select @error('categoria') is-invalid @enderror"
                                id="categoria"
                                name="categoria"
                                required>
                            <option value="">-- Seleccionar categoría --</option>
                            <option value="general" {{old('categoria') === 'general' ? 'selected' : ''}}>General</option>
                            <option value="empresa" {{old('categoria') === 'empresa' ? 'selected' : ''}}>Empresa</option>
                            <option value="email" {{old('categoria') === 'email' ? 'selected' : ''}}>Email</option>
                            <option value="sitio" {{old('categoria') === 'sitio' ? 'selected' : ''}}>Sitio</option>
                            <option value="sistema" {{old('categoria') === 'sistema' ? 'selected' : ''}}>Sistema</option>
                            <option value="otro" {{old('categoria') === 'otro' ? 'selected' : ''}}>Otro</option>
                        </select>
                        @error('categoria')
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
                                  placeholder="Describa para qué sirve esta configuración">{{old('descripcion')}}</textarea>
                        @error('descripcion')
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
                        <small class="form-text text-muted">Marque para activar esta configuración</small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Guardar Configuración
                    </button>
                    <a href="{{route("configuracion.index")}}" class="btn btn-secondary">
                        <i class="fa fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Ayuda</h5>
                </div>
                <div class="card-body">
                    <h6>Tipos de Configuración</h6>
                    <ul class="small">
                        <li><strong>Texto:</strong> Texto simple de una línea</li>
                        <li><strong>Email:</strong> Dirección de correo electrónico</li>
                        <li><strong>URL:</strong> Enlace web</li>
                        <li><strong>Número:</strong> Valor numérico</li>
                        <li><strong>Texto Largo:</strong> Múltiples líneas</li>
                        <li><strong>Sí/No:</strong> Valor booleano</li>
                    </ul>

                    <hr>

                    <h6>Categorías</h6>
                    <ul class="small">
                        <li><strong>General:</strong> Configuraciones generales</li>
                        <li><strong>Empresa:</strong> Datos de empresa</li>
                        <li><strong>Email:</strong> Configuración de correo</li>
                        <li><strong>Sitio:</strong> Configuración web</li>
                        <li><strong>Sistema:</strong> Configuración técnica</li>
                        <li><strong>Otro:</strong> Otras configuraciones</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function actualizarCampoValor() {
            const tipo = document.getElementById('tipo').value;
            const campoValor = document.getElementById('valor');
            const contenedor = document.getElementById('contenedorValor');

            // Limpiar clases antigas
            campoValor.className = 'form-control';

            if (tipo === 'email') {
                campoValor.type = 'email';
                campoValor.placeholder = 'ej: info@empresa.com';
            } else if (tipo === 'url') {
                campoValor.type = 'url';
                campoValor.placeholder = 'ej: https://www.empresa.com';
            } else if (tipo === 'number') {
                campoValor.type = 'number';
                campoValor.placeholder = 'ej: 100';
            } else if (tipo === 'textarea') {
                const textarea = document.createElement('textarea');
                textarea.className = 'form-control';
                textarea.id = 'valor';
                textarea.name = 'valor';
                textarea.rows = 4;
                textarea.placeholder = 'Ingrese el valor';
                textarea.value = campoValor.value;
                campoValor.replaceWith(textarea);
            } else {
                campoValor.type = 'text';
                campoValor.placeholder = 'Ingrese el valor';
            }

            // Si el tipo anterior era textarea, volver a crear input
            if (tipo !== 'textarea' && document.getElementById('valor').tagName === 'TEXTAREA') {
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control';
                input.id = 'valor';
                input.name = 'valor';
                input.placeholder = 'Ingrese el valor';
                input.value = document.getElementById('valor').value;
                document.getElementById('valor').replaceWith(input);
            }
        }

        // Ejecutar al cargar por si hay valor previo
        document.addEventListener('DOMContentLoaded', actualizarCampoValor);
    </script>
@endsection
