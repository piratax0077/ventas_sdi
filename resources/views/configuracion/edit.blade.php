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
@section("titulo", "Editar Configuración")
@section("contenido")
    <div class="row">
        <div class="col-lg-8">
            <h1>Editar Configuración <i class="fa fa-edit"></i></h1>

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

            <form action="{{route("configuracion.update", $configuracion)}}" method="post" class="card">
                @csrf
                @method("put")

                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">Actualizar Configuración: <code>{{$configuracion->clave}}</code></h5>
                </div>

                <div class="card-body">
                    <!-- Clave (ReadOnly) -->
                    <div class="mb-3">
                        <label for="clave" class="form-label">Clave (No editable)</label>
                        <input type="text"
                               class="form-control"
                               id="clave"
                               value="{{$configuracion->clave}}"
                               disabled>
                        <small class="form-text text-muted">La clave no puede ser modificada</small>
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
                            <option value="text" {{$configuracion->tipo === 'text' ? 'selected' : ''}}>Texto</option>
                            <option value="email" {{$configuracion->tipo === 'email' ? 'selected' : ''}}>Email</option>
                            <option value="url" {{$configuracion->tipo === 'url' ? 'selected' : ''}}>URL</option>
                            <option value="number" {{$configuracion->tipo === 'number' ? 'selected' : ''}}>Número</option>
                            <option value="textarea" {{$configuracion->tipo === 'textarea' ? 'selected' : ''}}>Texto Largo</option>
                            <option value="boolean" {{$configuracion->tipo === 'boolean' ? 'selected' : ''}}>Sí/No</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Valor -->
                    <div class="mb-3" id="contenedorValor">
                        <label for="valor" class="form-label">Valor</label>
                        @if($configuracion->tipo === 'textarea')
                            <textarea class="form-control @error('valor') is-invalid @enderror"
                                      id="valor"
                                      name="valor"
                                      rows="4"
                                      placeholder="Ingrese el valor">{{old('valor', $configuracion->valor)}}</textarea>
                        @elseif($configuracion->tipo === 'boolean')
                            <select class="form-select @error('valor') is-invalid @enderror"
                                    id="valor"
                                    name="valor">
                                <option value="">-- Seleccionar --</option>
                                <option value="1" {{$configuracion->valor === '1' || $configuracion->valor === 1 || $configuracion->valor === true ? 'selected' : ''}}>Sí</option>
                                <option value="0" {{$configuracion->valor === '0' || $configuracion->valor === 0 || $configuracion->valor === false ? 'selected' : ''}}>No</option>
                            </select>
                        @else
                            <input type="{{$configuracion->tipo === 'email' ? 'email' : ($configuracion->tipo === 'url' ? 'url' : ($configuracion->tipo === 'number' ? 'number' : 'text'))}}"
                                   class="form-control @error('valor') is-invalid @enderror"
                                   id="valor"
                                   name="valor"
                                   placeholder="Ingrese el valor"
                                   value="{{old('valor', $configuracion->valor)}}">
                        @endif
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
                            <option value="general" {{$configuracion->categoria === 'general' ? 'selected' : ''}}>General</option>
                            <option value="empresa" {{$configuracion->categoria === 'empresa' ? 'selected' : ''}}>Empresa</option>
                            <option value="email" {{$configuracion->categoria === 'email' ? 'selected' : ''}}>Email</option>
                            <option value="sitio" {{$configuracion->categoria === 'sitio' ? 'selected' : ''}}>Sitio</option>
                            <option value="sistema" {{$configuracion->categoria === 'sistema' ? 'selected' : ''}}>Sistema</option>
                            <option value="otro" {{$configuracion->categoria === 'otro' ? 'selected' : ''}}>Otro</option>
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
                                  placeholder="Describa para qué sirve esta configuración">{{old('descripcion', $configuracion->descripcion)}}</textarea>
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
                                   {{$configuracion->estado ? 'checked' : ''}}>
                            <label class="form-check-label" for="estado">
                                Activo
                            </label>
                        </div>
                        <small class="form-text text-muted">Marque para activar esta configuración</small>
                    </div>

                    <!-- Información de auditoría -->
                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="fa fa-info-circle"></i>
                            Creado: {{$configuracion->created_at->format('d/m/Y H:i')}}<br>
                            Última modificación: {{$configuracion->updated_at->format('d/m/Y H:i')}}
                        </small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Actualizar Configuración
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
                    <h5 class="mb-0">Información</h5>
                </div>
                <div class="card-body">
                    <h6>Detalles Actuales</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <small class="text-muted">Tipo</small><br>
                            <strong>{{ucfirst($configuracion->tipo)}}</strong>
                        </div>
                        <div class="list-group-item">
                            <small class="text-muted">Categoría</small><br>
                            <span class="badge bg-secondary">{{ucfirst($configuracion->categoria)}}</span>
                        </div>
                        <div class="list-group-item">
                            <small class="text-muted">Estado</small><br>
                            @if($configuracion->estado)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <h6>Acciones</h6>
                    <a href="{{route("configuracion.show", $configuracion)}}" class="btn btn-sm btn-info w-100 mb-2">
                        <i class="fa fa-eye"></i> Ver Detalles
                    </a>
                    <form action="{{route("configuracion.destroy", $configuracion)}}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('¿Está seguro de que desea eliminar esta configuración?')">
                            <i class="fa fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function actualizarCampoValor() {
            const tipo = document.getElementById('tipo').value;
            const campoValor = document.getElementById('valor');
            const contenedor = document.getElementById('contenedorValor');
            const valorActual = campoValor.value || (campoValor.tagName === 'TEXTAREA' ? campoValor.textContent : '');

            contenedor.innerHTML = '';

            if (tipo === 'textarea') {
                const textarea = document.createElement('textarea');
                textarea.className = 'form-control';
                textarea.id = 'valor';
                textarea.name = 'valor';
                textarea.rows = 4;
                textarea.placeholder = 'Ingrese el valor';
                textarea.textContent = valorActual;
                contenedor.appendChild(textarea);
            } else if (tipo === 'boolean') {
                const select = document.createElement('select');
                select.className = 'form-select';
                select.id = 'valor';
                select.name = 'valor';
                select.innerHTML = `
                    <option value="">-- Seleccionar --</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                `;
                select.value = valorActual;
                contenedor.appendChild(select);
            } else {
                const input = document.createElement('input');
                input.type = tipo === 'email' ? 'email' : (tipo === 'url' ? 'url' : (tipo === 'number' ? 'number' : 'text'));
                input.className = 'form-control';
                input.id = 'valor';
                input.name = 'valor';
                input.placeholder = 'Ingrese el valor';
                input.value = valorActual;
                contenedor.appendChild(input);
            }
        }

        // Ejecutar al cargar si el tipo es textarea
        document.addEventListener('DOMContentLoaded', function() {
            const tipo = document.getElementById('tipo').value;
            if (tipo === 'textarea') {
                actualizarCampoValor();
            }
        });
    </script>
@endsection
