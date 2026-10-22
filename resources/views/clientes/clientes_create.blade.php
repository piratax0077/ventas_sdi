@extends("maestra")
@section("titulo", "Agregar cliente")
@section("contenido")

<style>
    /* ── Wizard card ── */
    .wizard-card {
        max-width: 700px;
        margin: 0 auto;
        border-radius: .5rem;
        box-shadow: 0 4px 18px rgba(0,0,0,.12);
    }
    .wizard-card .card-top {
        background: #343a40;
        color: #fff;
        padding: 1.1rem 1.5rem .8rem;
        border-radius: .5rem .5rem 0 0;
        text-align: center;
    }
    .wizard-card .card-top h5 { margin-bottom: .2rem; font-size: 1.15rem; }
    .wizard-card .card-top p  { margin: 0; font-size: .85rem; opacity: .8; }

    /* ── Progress bar ── */
    .wizard-progress { border-radius: 0; height: 8px; }
    .wizard-progress .progress-bar { transition: width .4s ease; background-color: #28a745; }

    /* ── Step indicators ── */
    .wizard-steps {
        display: flex;
        justify-content: center;
        gap: 2.5rem;
        padding: .65rem 0 .3rem;
        border-bottom: 1px solid #e9ecef;
        margin-bottom: 1.2rem;
    }
    .wizard-steps .step-btn {
        background: none;
        border: none;
        font-size: .82rem;
        color: #adb5bd;
        cursor: default;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
    }
    .wizard-steps .step-btn .step-circle {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: #dee2e6;
        color: #6c757d;
        display: flex; align-items: center; justify-content: center;
        font-weight: 600; font-size: .78rem;
        transition: background .3s, color .3s;
    }
    .wizard-steps .step-btn.active .step-circle { background: #343a40; color: #fff; }
    .wizard-steps .step-btn.done   .step-circle { background: #28a745; color: #fff; }
    .wizard-steps .step-btn.active span:last-child,
    .wizard-steps .step-btn.done   span:last-child { color: #343a40; font-weight: 600; }

    /* ── Tab panes ── */
    .wizard-pane { display: none; }
    .wizard-pane.active { display: block; }

    /* ── Tarjetas de rol ── */
    .role-cards {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
        margin: 1rem 0 1.5rem;
    }
    .role-card {
        width: 180px;
        flex: 0 0 180px;
        border: 2px solid #dee2e6;
        border-radius: .5rem;
        padding: 1.4rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, box-shadow .2s;
        user-select: none;
    }
    .role-card:hover { border-color: #adb5bd; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .role-card.selected { border-color: #343a40; box-shadow: 0 2px 12px rgba(0,0,0,.15); background: #f8f9fa; }
    .role-card.advancing { border-color: #28a745; box-shadow: 0 2px 14px rgba(40,167,69,.3); background: #f0fff4; }

    /* ── Rol seleccionado (badge) ── */
    .selected-role-badge {
        background: #e8f5e9;
        border-left: 4px solid #28a745;
        padding: .5rem .8rem;
        margin: 1rem 0 1rem;
        font-size: .85rem;
        border-radius: .25rem;
        display: none;
    }
    .selected-role-badge.visible { display: block; }
    .selected-role-badge strong { color: #28a745; }
    .role-card .role-icon { font-size: 2rem; margin-bottom: .5rem; }
    .role-card .role-label { font-weight: 600; font-size: .9rem; color: #343a40; }
    .role-card .role-desc  { font-size: .75rem; color: #6c757d; margin-top: .2rem; }
    .role-error { color: #dc3545; font-size: .8rem; text-align: center; display: none; margin-top: -.5rem; margin-bottom: .5rem; }

    /* ── Nav buttons ── */
    .wizard-nav {
        display: flex;
        justify-content: flex-end;
        gap: .5rem;
        margin-top: 1.4rem;
    }

    /* ── Field labels ── */
    .floating-label { font-size: .83rem; font-weight: 500; color: #495057; margin-bottom: .25rem; }

    /* ── Validation ── */
    .is-invalid-field { border-color: #dc3545 !important; }
    .field-error { color: #dc3545; font-size: .75rem; margin-top: .2rem; display: none; }
</style>

<div class="wizard-card card border-0">

    {{-- Cabecera --}}
    <div class="card-top">
        <h5>Nuevo cliente</h5>
        <p>Complete los datos para finalizar el registro</p>
    </div>

    {{-- Barra de progreso --}}
    <div class="progress wizard-progress">
        <div class="progress-bar" id="wizardBar" role="progressbar"
             style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert" style="border-radius:0">
        <strong>Error:</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card-body px-4 pb-4">

        {{-- Indicadores de paso --}}
        <div class="wizard-steps" id="stepsIndicator">
            <button type="button" class="step-btn active" id="stepBtn1">
                <span class="step-circle">1</span>
                <span>Rol</span>
            </button>
            <button type="button" class="step-btn" id="stepBtn2">
                <span class="step-circle">2</span>
                <span>Personal</span>
            </button>
            <button type="button" class="step-btn" id="stepBtn3">
                <span class="step-circle">3</span>
                <span>Contacto</span>
            </button>
        </div>

        {{-- Formulario único envuelve todos los pasos --}}
        <form method="POST" action="{{ route('clientes.store') }}" id="wizardForm" novalidate>
            @csrf
            {{-- Campo oculto para el rol seleccionado --}}
            <input type="hidden" name="rol_id" id="rolSeleccionadoInput">

            {{-- ══════════════════════════════════
                 PASO 1 — Selección de rol
            ══════════════════════════════════ --}}
            <div class="wizard-pane active" id="pane1">
                <div class="form-row mb-1">
                    <div class="col-12 text-center mb-3">
                        <h6 class="font-weight-bold">Seleccione el tipo de cliente</h6>
                    </div>
                </div>

                <div class="role-cards" id="roleCards">
                    @php
                        $rolPaciente    = $roles->first(fn($r) => str_contains(strtolower($r->name), 'paciente'));
                        $rolProfesional = $roles->first(fn($r) => str_contains(strtolower($r->name), 'profesional'));
                        $rolAsistente   = $roles->first(fn($r) => str_contains(strtolower($r->name), 'asistente'));
                        $rolInstitucion = $roles->first(fn($r) => str_contains(strtolower($r->name), 'instit'));
                        $rolServicio    = $roles->first(fn($r) => str_contains(strtolower($r->name), 'servicio'));
                        $rolesMostrar   = collect(array_filter([$rolPaciente, $rolProfesional, $rolAsistente, $rolInstitucion, $rolServicio]));
                        if ($rolesMostrar->isEmpty()) {
                            $rolesMostrar = $roles->unique('name')->take(5)->values();
                        }
                    @endphp

                    @foreach($rolesMostrar as $rol)
                        @php
                            $nombre = strtolower($rol->name);
                            if      (str_contains($nombre, 'paciente'))    $tipo = 'paciente';
                            elseif  (str_contains($nombre, 'profesional')) $tipo = 'profesional';
                            elseif  (str_contains($nombre, 'asistente'))   $tipo = 'asistente';
                            elseif  (str_contains($nombre, 'instit'))      $tipo = 'institucion';
                            elseif  (str_contains($nombre, 'servicio'))    $tipo = 'servicio';
                            else                                            $tipo = $nombre;
                        @endphp
                        <div class="role-card"
                             data-rol-id="{{ $rol->id }}"
                             data-rol-nombre="{{ $tipo }}"
                             onclick="seleccionarRol(this)">
                            <div class="role-icon">
                                @if($tipo === 'paciente')
                                    &#128100;
                                @elseif($tipo === 'profesional')
                                    &#128203;
                                @elseif($tipo === 'institucion')
                                    &#127968;
                                @elseif($tipo === 'servicio')
                                    &#127973;
                                @else
                                    &#129309;
                                @endif
                            </div>
                            <div class="role-label">{{ ucfirst($tipo) }}</div>
                            <div class="role-desc">
                                @if($tipo === 'paciente')
                                    Registro de datos clínicos
                                @elseif($tipo === 'profesional')
                                    Registro profesional
                                @elseif($tipo === 'institucion')
                                    Registro institucional
                                @elseif($tipo === 'servicio')
                                    Registro de servicio
                                @else
                                    Apoyo y gestión
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="role-error" id="err_rol">Seleccione un tipo de cliente para continuar</div>

                <div class="wizard-nav">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('clientes.index') }}">Cancelar</a>
                </div>
            </div>

            {{-- ══════════════════════════════════
                 PASO 2 — Información personal (Paciente y Profesional)
            ══════════════════════════════════ --}}
            <div class="wizard-pane" id="pane2">
                <div class="selected-role-badge" id="badgePaso2">
                    <strong>✓ Rol seleccionado:</strong> <span id="rolNameBadge">Paciente</span>
                </div>

                <div class="form-row mb-2">
                    <div class="col-12 text-center mb-3">
                        <h6 class="font-weight-bold">Información personal</h6>
                    </div>
                </div>

                <div class="form-group row">
                    <span class="alert alert-success col-12" id="texto_verificacion" style="display:none"></span>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="rut">Rut</label>
                        <input type="text" class="form-control form-control-sm" name="rut" id="rut"
                               autocomplete="off" placeholder="12345678-9" oninput="formatoRut(this)" value="{{ old('rut') }}">
                        <div class="field-error" id="err_rut">Ingrese el RUT</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="nombre">Nombre</label>
                        <input type="text" class="form-control form-control-sm" name="nombre" id="nombre"
                               autocomplete="off" value="{{ old('nombre') }}">
                        <div class="field-error" id="err_nombre">Ingrese el nombre</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="primer_apellido">Primer apellido</label>
                        <input type="text" class="form-control form-control-sm" name="primer_apellido"
                               id="primer_apellido" autocomplete="off" value="{{ old('primer_apellido') }}">
                        <div class="field-error" id="err_primer_apellido">Ingrese el primer apellido</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="segundo_apellido">Segundo apellido</label>
                        <input type="text" class="form-control form-control-sm" name="segundo_apellido"
                               id="segundo_apellido" autocomplete="off" value="{{ old('segundo_apellido') }}">
                        <div class="field-error" id="err_segundo_apellido">Ingrese el segundo apellido</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label" for="fecha_nacimiento">Nacimiento</label>
                        <input type="date" class="form-control form-control-sm" name="fecha_nacimiento"
                               id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                        <div class="field-error" id="err_fecha">Ingrese la fecha de nacimiento</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label" for="sexo">Sexo</label>
                        <select class="form-control form-control-sm" name="sexo" id="sexo">
                            <option value="">Seleccione</option>
                            <option value="M" {{ old('sexo') === 'M' ? 'selected' : '' }}>Hombre</option>
                            <option value="F" {{ old('sexo') === 'F' ? 'selected' : '' }}>Mujer</option>
                        </select>
                        <div class="field-error" id="err_sexo">Seleccione el sexo</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-4 paciente-only" id="col_prevision" style="display:none">
                        <label class="floating-label" for="prevision_id">Previsión</label>
                        <select class="form-control form-control-sm" name="prevision_id" id="prevision_id">
                            <option value="">Seleccione</option>
                            @foreach($previsiones as $prevision)
                                <option value="{{ $prevision->id }}"
                                    {{ old('prevision_id') == $prevision->id ? 'selected' : '' }}>
                                    {{ $prevision->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-error" id="err_prevision">Seleccione una previsión</div>
                    </div>
                </div>

                {{-- Campos exclusivos del profesional --}}
                <div class="form-row profesional-only" id="row_profesion" style="display:none">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="profesion">Profesión</label>
                        <select class="form-control form-control-sm" name="profesion" id="profesion"
                                onchange="buscarTipoEspecialidad()">
                            <option value="">Seleccione</option>
                            @foreach($especialidades as $esp)
                                <option value="{{ $esp->id }}"
                                    {{ old('profesion') == $esp->id ? 'selected' : '' }}>
                                    {{ $esp->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-error" id="err_profesion">Ingrese la profesión</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="especialidad">Especialidad</label>
                        <select class="form-control form-control-sm" name="especialidad" id="especialidad"
                                onchange="buscarSubTipoEspecialidad()">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="field-error" id="err_especialidad">Seleccione una especialidad</div>
                    </div>
                </div>
                <div class="form-row profesional-only" id="row_subespecialidad" style="display:none">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="sub_tipo_especialidad">Sub-especialidad</label>
                        <select class="form-control form-control-sm" name="sub_tipo_especialidad" id="sub_tipo_especialidad">
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>

                {{-- Campos exclusivos de asistentes --}}
                <div class="form-row asistente-only" id="row_asistente" style="display:none">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="tipo_asistente">Tipo de asistente</label>
                        <select class="form-control form-control-sm" name="tipo_asistente" id="tipo_asistente">
                            <option value="">Seleccione</option>
                            @foreach($tipo_asistente as $tipo)
                                <option value="{{ $tipo->id }}"
                                    {{ old('tipo_asistente') == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-error" id="err_tipo_asistente">Seleccione un tipo de asistente</div>
                    </div>
                </div>

                <div class="wizard-nav">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnBack2">&lsaquo; Atrás</button>
                    <button type="button" class="btn btn-dark btn-sm" id="btnNext2">Siguiente &rsaquo;</button>
                </div>
            </div>

            {{-- ══════════════════════════════════
                 PASO 3 — Residencia y contacto (compartido)
            ══════════════════════════════════ --}}
            <div class="wizard-pane" id="pane3">
                <div class="selected-role-badge" id="badgePaso3">
                    <strong>✓ Rol seleccionado:</strong> <span id="rolNameBadge2">Paciente</span>
                </div>

                <div class="form-row mb-2">
                    <div class="col-12 text-center mb-3">
                        <h6 class="font-weight-bold">Residencia y contacto</h6>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-8">
                        <label class="floating-label" for="direccion">Dirección</label>
                        <input type="text" class="form-control form-control-sm" name="direccion" id="direccion"
                               autocomplete="off" value="{{ old('direccion') }}">
                        <div class="field-error" id="err_direccion">Ingrese la dirección</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label" for="numero_dir">Nº</label>
                        <input type="number" class="form-control form-control-sm" name="numero_dir"
                               id="numero_dir" autocomplete="off" value="{{ old('numero_dir') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="region_id">Región</label>
                        <select class="form-control form-control-sm" name="region_id" id="region_id" onchange="buscarCiudades(this.value)">
                            <option value="">Seleccione</option>
                            @foreach($regiones as $region)
                                <option value="{{ $region->id }}"
                                    {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                    {{ $region->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-error" id="err_region">Seleccione una región</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="ciudad_id">Comuna</label>
                        <select class="form-control form-control-sm" name="ciudad_id" id="ciudad_id">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="field-error" id="err_ciudad">Seleccione una comuna</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="celular">Celular</label>
                        <input type="tel" class="form-control form-control-sm" name="celular" id="celular"
                               autocomplete="off" placeholder="+569..." value="{{ old('celular') }}">
                        <div class="field-error" id="err_celular">Ingrese el celular</div>
                    </div>
                    {{-- <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="telefono">Teléfono</label>
                        <input type="tel" class="form-control form-control-sm" name="telefono" id="telefono"
                               autocomplete="off" placeholder="+562..." value="{{ old('telefono') }}">
                    </div> --}}
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="correo">Correo electrónico</label>
                        <input type="email" class="form-control form-control-sm" name="correo" id="correo"
                               autocomplete="off" value="{{ old('correo') }}">
                        <div class="field-error" id="err_correo">Ingrese un correo válido</div>
                    </div>
                </div>

                @include("notificacion")

                <div class="wizard-nav">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnBack3">&lsaquo; Atrás</button>
                    <button type="submit" class="btn btn-success btn-sm">Guardar cliente</button>
                </div>
            </div>

            {{-- ══════════════════════════════════
                 PASO 2 — INSTITUCIÓN: Datos institucionales
            ══════════════════════════════════ --}}
            <div class="wizard-pane" id="pane2_institucion">
                <div class="selected-role-badge" id="badgePaso2Inst">
                    <strong>✓ Rol seleccionado:</strong> <span id="rolNameBadgeInst">Institución</span>
                </div>

                <div class="form-row mb-2">
                    <div class="col-12 text-center mb-3">
                        <h6 class="font-weight-bold">Datos de la institución</h6>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="inst_rut">Rut institución</label>
                        <input type="text" class="form-control form-control-sm" name="inst_rut" id="inst_rut"
                               autocomplete="off" placeholder="12345678-9" oninput="formatoRut(this)"
                               value="{{ old('inst_rut') }}">
                        <div class="field-error" id="err_inst_rut">Ingrese el RUT de la institución</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="nombre_institucion">Nombre institución</label>
                        <input type="text" class="form-control form-control-sm" name="nombre_institucion" id="nombre_institucion"
                               autocomplete="off" value="{{ old('nombre_institucion') }}">
                        <div class="field-error" id="err_nombre_institucion">Ingrese el nombre de la institución</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="tipo_institucion">Tipo de institución</label>
                        <select class="form-control form-control-sm" id="tipo_institucion" name="tipo_institucion" onchange="dame_subtipos_institucion()">
                            <option selected value="0">Seleccione</option>
                            @if (isset($tipo_institucion))
                                @foreach ($tipo_institucion as $t_inst)
                                    <option value="{{ $t_inst->id }}">{{ $t_inst->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="field-error" id="err_tipo_institucion">Ingrese el tipo de institución</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6 d-none" id="div_subtipo_institucion">
                        <label class="floating-label" for="subtipo_institucion">Subtipo de institución</label>
                        <select class="form-control form-control-sm" id="subtipo_institucion" name="subtipo_institucion">
                            <option selected value="0">Seleccione</option>
                        </select>
                        <div class="field-error" id="err_subtipo_institucion">Ingrese el subtipo de institución</div>

                        <div class="field-error" id="err_tipo_institucion">Ingrese el tipo de institución</div>
                    </div>
                </div>

                <hr class="my-2">
                <div class="form-row mb-1">
                    <div class="col-12 text-center"><small class="text-muted font-weight-bold">Dirección</small></div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-8">
                        <label class="floating-label" for="inst_direccion">Dirección</label>
                        <input type="text" class="form-control form-control-sm" name="inst_direccion" id="inst_direccion"
                               autocomplete="off" value="{{ old('inst_direccion') }}">
                        <div class="field-error" id="err_inst_direccion">Ingrese la dirección</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label" for="inst_numero_dir">Nº</label>
                        <input type="number" class="form-control form-control-sm" name="inst_numero_dir" id="inst_numero_dir"
                               autocomplete="off" value="{{ old('inst_numero_dir') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="inst_region_id">Región</label>
                        <select class="form-control form-control-sm" name="inst_region_id" id="inst_region_id"
                                onchange="buscarCiudadesInst(this.value)">
                            <option value="">Seleccione</option>
                            @foreach($regiones as $region)
                                <option value="{{ $region->id }}"
                                    {{ old('inst_region_id') == $region->id ? 'selected' : '' }}>
                                    {{ $region->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-error" id="err_inst_region">Seleccione una región</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="inst_ciudad_id">Comuna</label>
                        <select class="form-control form-control-sm" name="inst_ciudad_id" id="inst_ciudad_id">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="field-error" id="err_inst_ciudad">Seleccione una comuna</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="inst_telefono">Teléfono</label>
                        <input type="tel" class="form-control form-control-sm" name="inst_telefono" id="inst_telefono"
                               autocomplete="off" placeholder="+562..."
                               value="{{ old('inst_telefono') }}">
                        <div class="field-error" id="err_inst_telefono">Ingrese el teléfono de la institución</div>
                    </div>
                </div>

                <div class="wizard-nav">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnBack2Inst">&lsaquo; Atrás</button>
                    <button type="button" class="btn btn-dark btn-sm" id="btnNext2Inst">Siguiente &rsaquo;</button>
                </div>
            </div>

            {{-- ══════════════════════════════════
                 PASO 3 — INSTITUCIÓN: Datos del responsable
            ══════════════════════════════════ --}}
            <div class="wizard-pane" id="pane3_institucion">
                <div class="selected-role-badge" id="badgePaso3Inst">
                    <strong>✓ Rol seleccionado:</strong> <span id="rolNameBadgeInst2">Institución</span>
                </div>

                <div class="form-row mb-2">
                    <div class="col-12 text-center mb-3">
                        <h6 class="font-weight-bold">Datos del responsable</h6>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="responsable_rut">Rut responsable</label>
                        <input type="text" class="form-control form-control-sm" name="responsable_rut" id="responsable_rut"
                               autocomplete="off" placeholder="12345678-9"
                               value="{{ old('responsable_rut') }}">
                        <div class="field-error" id="err_responsable_rut">Ingrese el RUT del responsable</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="responsable_nombre">Nombre responsable</label>
                        <input type="text" class="form-control form-control-sm" name="responsable_nombre" id="responsable_nombre"
                               autocomplete="off" value="{{ old('responsable_nombre') }}">
                        <div class="field-error" id="err_responsable_nombre">Ingrese el nombre del responsable</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="responsable_apellido_uno">Primer apellido</label>
                        <input type="text" class="form-control form-control-sm" name="responsable_apellido_uno" id="responsable_apellido_uno"
                               autocomplete="off" value="{{ old('responsable_apellido_uno') }}">
                        <div class="field-error" id="err_responsable_apellido_uno">Ingrese el primer apellido</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="responsable_apellido_dos">Segundo apellido</label>
                        <input type="text" class="form-control form-control-sm" name="responsable_apellido_dos" id="responsable_apellido_dos"
                               autocomplete="off" value="{{ old('responsable_apellido_dos') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="responsable_celular">Celular</label>
                        <input type="tel" class="form-control form-control-sm" name="responsable_celular" id="responsable_celular"
                               autocomplete="off" placeholder="+569..."
                               value="{{ old('responsable_celular') }}">
                        <div class="field-error" id="err_responsable_celular">Ingrese el celular del responsable</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="responsable_telefono">Teléfono</label>
                        <input type="tel" class="form-control form-control-sm" name="responsable_telefono" id="responsable_telefono"
                               autocomplete="off" placeholder="+562..."
                               value="{{ old('responsable_telefono') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="correo_inst">Correo electrónico</label>
                        <input type="email" class="form-control form-control-sm" name="correo_inst" id="correo_inst"
                               autocomplete="off" value="{{ old('correo_inst') }}">
                        <div class="field-error" id="err_correo_inst">Ingrese un correo válido</div>
                    </div>
                </div>

                @include("notificacion")

                <div class="wizard-nav">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnBack3Inst">&lsaquo; Atrás</button>
                    <button type="submit" class="btn btn-success btn-sm">Guardar cliente</button>
                </div>
            </div>

            {{-- ══════════════════════════════════
                 PASO 2 — SERVICIO: Datos del servicio
            ══════════════════════════════════ --}}
            <div class="wizard-pane" id="pane2_servicio">
                <div class="selected-role-badge visible" id="badgePaso2Serv">
                    <strong>✓ Rol seleccionado:</strong> <span>Servicio</span>
                </div>

                <div class="form-row mb-2">
                    <div class="col-12 text-center mb-3">
                        <h6 class="font-weight-bold">Datos del servicio</h6>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="serv_rut">Rut del servicio</label>
                        <input type="text" class="form-control form-control-sm" name="serv_rut" id="serv_rut"
                               autocomplete="off" placeholder="12345678-9" oninput="formatoRut(this)"
                               value="{{ old('serv_rut') }}">
                        <div class="field-error" id="err_serv_rut">Ingrese el RUT del servicio</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="nombre_servicio">Nombre del servicio</label>
                        <input type="text" class="form-control form-control-sm" name="nombre_servicio" id="nombre_servicio"
                               autocomplete="off" value="{{ old('nombre_servicio') }}">
                        <div class="field-error" id="err_nombre_servicio">Ingrese el nombre del servicio</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="tipo_servicio">Tipo de servicio</label>
                        <input type="text" class="form-control form-control-sm" name="tipo_servicio" id="tipo_servicio"
                               autocomplete="off" placeholder="Ej: Laboratorio, Imagenología..."
                               value="{{ old('tipo_servicio') }}">
                        <div class="field-error" id="err_tipo_servicio">Ingrese el tipo de servicio</div>
                    </div>
                </div>

                <hr class="my-2">
                <div class="form-row mb-1">
                    <div class="col-12 text-center"><small class="text-muted font-weight-bold">Dirección</small></div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-8">
                        <label class="floating-label" for="serv_direccion">Dirección</label>
                        <input type="text" class="form-control form-control-sm" name="serv_direccion" id="serv_direccion"
                               autocomplete="off" value="{{ old('serv_direccion') }}">
                        <div class="field-error" id="err_serv_direccion">Ingrese la dirección</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label" for="serv_numero_dir">Nº</label>
                        <input type="number" class="form-control form-control-sm" name="serv_numero_dir" id="serv_numero_dir"
                               autocomplete="off" value="{{ old('serv_numero_dir') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="serv_region_id">Región</label>
                        <select class="form-control form-control-sm" name="serv_region_id" id="serv_region_id"
                                onchange="buscarCiudadesServ(this.value)">
                            <option value="">Seleccione</option>
                            @foreach($regiones as $region)
                                <option value="{{ $region->id }}"
                                    {{ old('serv_region_id') == $region->id ? 'selected' : '' }}>
                                    {{ $region->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-error" id="err_serv_region">Seleccione una región</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="serv_ciudad_id">Comuna</label>
                        <select class="form-control form-control-sm" name="serv_ciudad_id" id="serv_ciudad_id">
                            <option value="">Seleccione</option>
                        </select>
                        <div class="field-error" id="err_serv_ciudad">Seleccione una comuna</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label" for="serv_telefono">Teléfono</label>
                        <input type="tel" class="form-control form-control-sm" name="serv_telefono" id="serv_telefono"
                               autocomplete="off" placeholder="+562..."
                               value="{{ old('serv_telefono') }}">
                    </div>
                </div>

                <div class="wizard-nav">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnBack2Serv">&lsaquo; Atrás</button>
                    <button type="button" class="btn btn-dark btn-sm" id="btnNext2Serv">Siguiente &rsaquo;</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
(function () {

    /* ─── Estado ─── */
    var rolActual   = null;
    var bar         = document.getElementById('wizardBar');
    var s1          = document.getElementById('stepBtn1');
    var s2          = document.getElementById('stepBtn2');
    var s3          = document.getElementById('stepBtn3');

    /* ─── Utilidades ─── */
    function setProgress(pct) {
        bar.style.width = pct + '%';
        bar.setAttribute('aria-valuenow', pct);
    }

    function hideAllPanes() {
        document.querySelectorAll('.wizard-pane').forEach(function (p) { p.classList.remove('active'); });
    }

    function showPane(id) {
        hideAllPanes();
        document.getElementById(id).classList.add('active');
    }

    function setStepState(btn, state) {
        btn.classList.remove('active', 'done');
        if (state) btn.classList.add(state);
    }

    /* ─── Selección de rol: auto-avanza al hacer clic ─── */
    window.seleccionarRol = function (el) {
        document.querySelectorAll('.role-card').forEach(function (c) { c.classList.remove('selected', 'advancing'); });
        el.classList.add('selected');
        rolActual = el.dataset.rolNombre;
        document.getElementById('rolSeleccionadoInput').value = el.dataset.rolId;
        document.getElementById('err_rol').style.display = 'none';

        // Actualizar badges con el rol seleccionado
        var rolLabel = el.querySelector('.role-label').textContent;
        document.getElementById('rolNameBadge').textContent    = rolLabel;
        document.getElementById('rolNameBadge2').textContent   = rolLabel;
        document.getElementById('rolNameBadgeInst').textContent  = rolLabel;
        document.getElementById('rolNameBadgeInst2').textContent = rolLabel;

        // Mostrar/ocultar secciones exclusivas de cada rol en pane2
        var pacienteRows    = document.querySelectorAll('.paciente-only');
        var profesionalRows = document.querySelectorAll('.profesional-only');
        var asistenteRows    = document.querySelectorAll('.asistente-only');
        if (rolActual === 'paciente') {
            pacienteRows.forEach(function (r) { r.style.display = ''; });
            profesionalRows.forEach(function (r) { r.style.display = 'none'; });
            asistenteRows.forEach(function (r) { r.style.display = 'none'; });
        } else if (rolActual === 'profesional') {
            pacienteRows.forEach(function (r) { r.style.display = 'none'; });
            profesionalRows.forEach(function (r) { r.style.display = ''; });
        }else if(rolActual === 'asistente') {
            pacienteRows.forEach(function (r) { r.style.display = 'none'; });
            profesionalRows.forEach(function (r) { r.style.display = 'none'; });
            asistenteRows.forEach(function (r) { r.style.display = ''; });

        }else {
            // asistente u otros: sin secciones exclusivas
            pacienteRows.forEach(function (r) { r.style.display = 'none'; });
            profesionalRows.forEach(function (r) { r.style.display = 'none'; });
            asistenteRows.forEach(function (r) { r.style.display = 'none'; });
        }

        // Breve feedback visual y luego avanza
        setTimeout(function () { el.classList.add('advancing'); }, 50);
        setTimeout(function () {
            setStepState(s1, 'done');
            setStepState(s2, 'active');
            setStepState(s3, null);
            setProgress(50);
            if (rolActual === 'institucion') {
                document.getElementById('badgePaso2Inst').classList.add('visible');
                document.getElementById('badgePaso3Inst').classList.add('visible');
                showPane('pane2_institucion');
            } else if (rolActual === 'servicio') {
                document.getElementById('badgePaso2Serv').classList.add('visible');
                document.getElementById('badgePaso3Inst').classList.add('visible');
                document.getElementById('rolNameBadgeInst2').textContent = 'Servicio';
                showPane('pane2_servicio');
            } else {
                document.getElementById('badgePaso2').classList.add('visible');
                document.getElementById('badgePaso3').classList.add('visible');
                showPane('pane2');
            }
        }, 220);
    };

    /* ─── Atrás desde paso 2 ─── */
    document.getElementById('btnBack2').addEventListener('click', function () {
        document.getElementById('badgePaso2').classList.remove('visible');
        document.getElementById('badgePaso3').classList.remove('visible');
        setStepState(s1, 'active');
        setStepState(s2, null);
        setStepState(s3, null);
        setProgress(33);
        showPane('pane1');
    });

    /* ─── Paso 2 → Paso 3 ─── */
    document.getElementById('btnNext2').addEventListener('click', function () {
        if (!validatePersonal()) return;
        setStepState(s2, 'done');
        setStepState(s3, 'active');
        setProgress(100);
        showPane('pane3');
    });

    /* ─── Atrás desde paso 3 ─── */
    document.getElementById('btnBack3').addEventListener('click', function () {
        setStepState(s2, 'active');
        setStepState(s3, null);
        setProgress(50);
        showPane('pane2');
    });

    /* ─── Validación paso personal ─── */
    function validatePersonal() {
        var ok = true;

        function check(id, errId) {
            var el  = document.getElementById(id);
            var err = document.getElementById(errId);
            if (!el) return;
            var val = el.value.trim();
            if (!val || val === '' || val === '0') {
                el.classList.add('is-invalid-field');
                err.style.display = 'block';
                ok = false;
            } else {
                el.classList.remove('is-invalid-field');
                err.style.display = 'none';
            }
        }

        // Campos comunes a ambos roles
        check('rut',             'err_rut');
        check('nombre',          'err_nombre');
        check('primer_apellido', 'err_primer_apellido');
        check('segundo_apellido','err_segundo_apellido');
        check('fecha_nacimiento','err_fecha');
        check('sexo',            'err_sexo');

        // Campo exclusivo de paciente
        if (rolActual === 'paciente') {
            check('prevision_id', 'err_prevision');
        }

        // Campo exclusivo de profesional
        if (rolActual === 'profesional') {
            check('profesion', 'err_profesion');
        }

        return ok;
    }

    /* ─── Validación paso contacto ─── */
    function validateContacto() {
        var ok = true;

        function check(id, errId) {
            var el  = document.getElementById(id);
            var err = document.getElementById(errId);
            if (!el.value.trim()) {
                el.classList.add('is-invalid-field');
                err.style.display = 'block';
                ok = false;
            } else {
                el.classList.remove('is-invalid-field');
                err.style.display = 'none';
            }
        }

        check('direccion', 'err_direccion');
        check('region_id', 'err_region');
        check('ciudad_id', 'err_ciudad');
        check('celular',   'err_celular');
        check('correo',    'err_correo');
        return ok;
    }

    /* ─── INSTITUCIÓN: Atrás desde paso 2 ─── */
    document.getElementById('btnBack2Inst').addEventListener('click', function () {
        document.getElementById('badgePaso2Inst').classList.remove('visible');
        document.getElementById('badgePaso3Inst').classList.remove('visible');
        setStepState(s1, 'active');
        setStepState(s2, null);
        setStepState(s3, null);
        setProgress(33);
        showPane('pane1');
    });

    /* ─── INSTITUCIÓN: Paso 2 → Paso 3 ─── */
    document.getElementById('btnNext2Inst').addEventListener('click', function () {
        if (!validateInstitucion()) return;
        setStepState(s2, 'done');
        setStepState(s3, 'active');
        setProgress(100);
        showPane('pane3_institucion');
    });

    /* ─── INSTITUCIÓN / SERVICIO: Atrás desde paso 3 ─── */
    document.getElementById('btnBack3Inst').addEventListener('click', function () {
        setStepState(s2, 'active');
        setStepState(s3, null);
        setProgress(50);
        if (rolActual === 'servicio') {
            showPane('pane2_servicio');
        } else {
            showPane('pane2_institucion');
        }
    });

    /* ─── SERVICIO: Atrás desde paso 2 ─── */
    document.getElementById('btnBack2Serv').addEventListener('click', function () {
        document.getElementById('badgePaso2Serv').classList.remove('visible');
        document.getElementById('badgePaso3Inst').classList.remove('visible');
        setStepState(s1, 'active');
        setStepState(s2, null);
        setStepState(s3, null);
        setProgress(33);
        showPane('pane1');
    });

    /* ─── SERVICIO: Paso 2 → Paso 3 ─── */
    document.getElementById('btnNext2Serv').addEventListener('click', function () {
        if (!validateServicio()) return;
        setStepState(s2, 'done');
        setStepState(s3, 'active');
        setProgress(100);
        showPane('pane3_institucion');
    });

    /* ─── Validación datos del servicio ─── */
    function validateServicio() {
        var ok = true;
        function check(id, errId) {
            var el  = document.getElementById(id);
            var err = document.getElementById(errId);
            if (!el || !err) return;
            if (!el.value.trim()) {
                el.classList.add('is-invalid-field');
                err.style.display = 'block';
                ok = false;
            } else {
                el.classList.remove('is-invalid-field');
                err.style.display = 'none';
            }
        }
        check('serv_rut',          'err_serv_rut');
        check('nombre_servicio',   'err_nombre_servicio');
        check('tipo_servicio',     'err_tipo_servicio');
        check('serv_direccion',    'err_serv_direccion');
        check('serv_region_id',    'err_serv_region');
        check('serv_ciudad_id',    'err_serv_ciudad');
        return ok;
    }

    /* ─── Validación datos institucionales ─── */
    function validateInstitucion() {
        var ok = true;
        function check(id, errId) {
            var el  = document.getElementById(id);
            var err = document.getElementById(errId);
            if (!el || !err) return;
            if (!el.value.trim()) {
                el.classList.add('is-invalid-field');
                err.style.display = 'block';
                ok = false;
            } else {
                el.classList.remove('is-invalid-field');
                err.style.display = 'none';
            }
        }
        check('inst_rut',           'err_inst_rut');
        check('nombre_institucion', 'err_nombre_institucion');
        check('tipo_institucion',   'err_tipo_institucion');
        check('inst_direccion',     'err_inst_direccion');
        check('inst_region_id',     'err_inst_region');
        check('inst_ciudad_id',     'err_inst_ciudad');
        return ok;
    }

    /* ─── Validación datos del responsable ─── */
    function validateResponsable() {
        var ok = true;
        function check(id, errId) {
            var el  = document.getElementById(id);
            var err = document.getElementById(errId);
            if (!el || !err) return;
            if (!el.value.trim()) {
                el.classList.add('is-invalid-field');
                err.style.display = 'block';
                ok = false;
            } else {
                el.classList.remove('is-invalid-field');
                err.style.display = 'none';
            }
        }
        check('responsable_rut',          'err_responsable_rut');
        check('responsable_nombre',        'err_responsable_nombre');
        check('responsable_apellido_uno',  'err_responsable_apellido_uno');
        check('responsable_celular',       'err_responsable_celular');
        check('correo_inst',               'err_correo_inst');
        return ok;
    }

    /* ─── Prevenir envío sin validar contacto ─── */
    document.getElementById('wizardForm').addEventListener('submit', function (e) {
        if (rolActual === 'institucion' || rolActual === 'servicio') {
            if (!validateResponsable()) e.preventDefault();
        } else if (!validateContacto()) {
            e.preventDefault();
        }
    });

    /* ─── Limpiar error al escribir / seleccionar ─── */
    document.querySelectorAll('.form-control').forEach(function (el) {
        el.addEventListener('input', function ()  { limpiarError(el); });
        el.addEventListener('change', function () { limpiarError(el); });
    });

    function limpiarError(el) {
        el.classList.remove('is-invalid-field');
        var errEl = document.getElementById('err_' + el.id);
        if (errEl) errEl.style.display = 'none';
    }

    /* ─── Restaurar estado del wizard tras errores del servidor ─── */
    window.restoreWizardState = function (rolId, errors) {
        var roleCard = document.querySelector('[data-rol-id="' + rolId + '"]');
        if (!roleCard) return;

        document.querySelectorAll('.role-card').forEach(function (c) { c.classList.remove('selected', 'advancing'); });
        roleCard.classList.add('selected');
        rolActual = roleCard.dataset.rolNombre;
        document.getElementById('rolSeleccionadoInput').value = rolId;

        var rolLabel = roleCard.querySelector('.role-label').textContent;
        ['rolNameBadge', 'rolNameBadge2', 'rolNameBadgeInst', 'rolNameBadgeInst2'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.textContent = rolLabel;
        });

        var pacienteRows    = document.querySelectorAll('.paciente-only');
        var profesionalRows = document.querySelectorAll('.profesional-only');
        if (rolActual === 'paciente') {
            pacienteRows.forEach(function (r) { r.style.display = ''; });
            profesionalRows.forEach(function (r) { r.style.display = 'none'; });
        } else if (rolActual === 'profesional') {
            pacienteRows.forEach(function (r) { r.style.display = 'none'; });
            profesionalRows.forEach(function (r) { r.style.display = ''; });
        } else {
            pacienteRows.forEach(function (r) { r.style.display = 'none'; });
            profesionalRows.forEach(function (r) { r.style.display = 'none'; });
        }

        /* Mapa de campos cuyo id de error no sigue el patrón err_{field} */
        var errIdMap = {
            'fecha_nacimiento' : 'err_fecha',
            'ciudad_id'        : 'err_ciudad',
            'prevision_id'     : 'err_prevision',
            'inst_ciudad_id'   : 'err_inst_ciudad',
            'serv_ciudad_id'   : 'err_serv_ciudad',
        };

        /* Campos que pertenecen al paso 2 (pane2 / pane2_institucion / pane2_servicio) */
        var step2Keys = [
            'rut','nombre','primer_apellido','segundo_apellido','fecha_nacimiento','sexo',
            'prevision_id','profesion','inst_rut','nombre_institucion','tipo_institucion',
            'inst_direccion','inst_ciudad_id','inst_telefono','serv_rut','nombre_servicio',
            'tipo_servicio','serv_direccion','serv_ciudad_id'
        ];
        var hasStep2Error = Object.keys(errors).some(function (k) { return step2Keys.indexOf(k) !== -1; });

        /* Marcar campos con error */
        Object.keys(errors).forEach(function (field) {
            var el = document.getElementById(field);
            if (el) el.classList.add('is-invalid-field');
            var errId = errIdMap[field] || ('err_' + field);
            var errEl = document.getElementById(errId);
            if (errEl) { errEl.textContent = errors[field]; errEl.style.display = 'block'; }
        });

        /* Actualizar indicadores de paso */
        setStepState(s1, 'done');
        if (hasStep2Error) {
            setStepState(s2, 'active');
            setStepState(s3, null);
            setProgress(50);
        } else {
            setStepState(s2, 'done');
            setStepState(s3, 'active');
            setProgress(100);
        }

        /* Navegar al panel correcto */
        if (rolActual === 'institucion') {
            document.getElementById('badgePaso2Inst').classList.add('visible');
            document.getElementById('badgePaso3Inst').classList.add('visible');
            showPane(hasStep2Error ? 'pane2_institucion' : 'pane3_institucion');
        } else if (rolActual === 'servicio') {
            document.getElementById('badgePaso2Serv').classList.add('visible');
            document.getElementById('badgePaso3Inst').classList.add('visible');
            showPane(hasStep2Error ? 'pane2_servicio' : 'pane3_institucion');
        } else {
            document.getElementById('badgePaso2').classList.add('visible');
            document.getElementById('badgePaso3').classList.add('visible');
            showPane(hasStep2Error ? 'pane2' : 'pane3');
        }
    };

})();

/* ─── Cargar comunas al cambiar región con AJAX ─── */


    function buscarCiudades(regionId) {
        var select = document.getElementById('ciudad_id');
        select.innerHTML = '<option value="">Seleccione</option>';
        if (!regionId) return;

        // Usar AJAX con jQuery
        $.ajax({
            url: "{{ route('home.buscar_ciudad_region') }}",
            type: "get",
            data: {
                region: regionId,
            },
            dataType: 'json'
        })
        .done(function (data) {
            if (data != null && Array.isArray(data)) {
                console.log(data);
                select.innerHTML = '<option value="">Seleccione</option>';
                data.forEach(function (ciudad) {
                    var opt = document.createElement('option');
                    opt.value = ciudad.id;
                    opt.textContent = ciudad.nombre;
                    select.appendChild(opt);
                });
            } else {
                console.log('No se pudieron cargar las ciudades');
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Error:', jqXHR, ajaxOptions, thrownError);
        });
    }

function formatoRut(rut)
{
    var valor = rut.value.replace('.','');
    valor = valor.replace(/\-/g,'');
    valor = valor.replace(/\ /g,'');
    valor = valor.replace(/[qwertyuiopasdfghjlñzxcvbnmQWERTYUIOPASDFGHJLÑZXCVBNM]/g,'');
    valor = valor.replace(/[/,´.*'+¿?^$!¡=&%"#¨_:;`~°{}()|[\]\\]/g,'');

    cuerpo = valor.slice(0,-1);
    dv = valor.slice(-1).toUpperCase();
    rut.value = cuerpo + '-'+ dv

    if(cuerpo.length < 7) { rut.setCustomValidity("RUT Incompleto"); return false;}

    suma = 0;
    multiplo = 2;

    for(i=1;i<=cuerpo.length;i++)
    {
        index = multiplo * valor.charAt(cuerpo.length - i);
        suma = suma + index;
        if(multiplo < 7) { multiplo = multiplo + 1; } else { multiplo = 2; }
    }

    dvEsperado = 11 - (suma % 11);
    dv = (dv == 'K')?10:dv;
    dv = (dv == 0)?11:dv;

    if(dvEsperado != dv) { rut.setCustomValidity("RUT Inválido"); return false; }

    rut.setCustomValidity('');
}

/* ─── Cargar especialidades al cambiar profesión ─── */
/* ─── Cargar comunas institución ─── */
    function buscarCiudadesInst(regionId) {
        var select = document.getElementById('inst_ciudad_id');
        select.innerHTML = '<option value="">Seleccione</option>';
        if (!regionId) return;
        $.ajax({
            url: "{{ route('home.buscar_ciudad_region') }}",
            type: 'get',
            data: { region: regionId },
            dataType: 'json'
        })
        .done(function (data) {
            if (data != null && Array.isArray(data)) {
                data.forEach(function (ciudad) {
                    var opt = document.createElement('option');
                    opt.value = ciudad.id;
                    opt.textContent = ciudad.nombre;
                    select.appendChild(opt);
                });
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Error:', jqXHR, ajaxOptions, thrownError);
        });
    }

/* ─── Cargar comunas servicio ─── */
    function buscarCiudadesServ(regionId) {
        var select = document.getElementById('serv_ciudad_id');
        select.innerHTML = '<option value="">Seleccione</option>';
        if (!regionId) return;
        $.ajax({
            url: "{{ route('home.buscar_ciudad_region') }}",
            type: 'get',
            data: { region: regionId },
            dataType: 'json'
        })
        .done(function (data) {
            if (data != null && Array.isArray(data)) {
                data.forEach(function (ciudad) {
                    var opt = document.createElement('option');
                    opt.value = ciudad.id;
                    opt.textContent = ciudad.nombre;
                    select.appendChild(opt);
                });
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Error:', jqXHR, ajaxOptions, thrownError);
        });
    }

function buscarTipoEspecialidad() {
    var $especialidad    = $('#especialidad');
    var $subEspecialidad = $('#sub_tipo_especialidad');

    $especialidad.html('<option value="">Seleccione</option>');
    $subEspecialidad.html('<option value="">Seleccione</option>');

    var profesionId = $('#profesion').val();
    if (!profesionId) return;

    $.ajax({
        url: "{{ route('home.buscar_especialidad') }}",
        type: 'get',
        data: { especialidad: profesionId }
    })
    .done(function (data) {
        if (data != null) {
            data = JSON.parse(data);
            $especialidad.html('<option value="">Seleccione</option>');
            if (data.length > 0) {
                $.each(data, function (i, v) {
                    $especialidad.append('<option value="' + v.id + '">' + v.nombre + '</option>');
                });
            } else {
                $especialidad.html('<option value="0">No Aplica</option>');
                $especialidad.val(0);
                $subEspecialidad.html('<option value="0">No Aplica</option>');
                $subEspecialidad.val(0);
            }
        } else {
            alert('No se pudieron cargar las especialidades');
        }
    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
        console.log(jqXHR, ajaxOptions, thrownError);
    });
}

/* ─── Cargar sub-especialidades al cambiar especialidad ─── */
function buscarSubTipoEspecialidad() {
    var $subEspecialidad = $('#sub_tipo_especialidad');
    $subEspecialidad.html('<option value="">Seleccione</option>');

    var especialidadId = $('#especialidad').val();
    if (!especialidadId || especialidadId == '0') return;

    $.ajax({
        url: "{{ route('home.buscar_sub_tipo_especialidad') }}",
        type: 'get',
        data: { tipo_especialidad: especialidadId }
    })
    .done(function (data) {
        if (data != null) {
            data = JSON.parse(data);
            $subEspecialidad.html('<option value="">Seleccione</option>');
            if (data.length > 0) {
                $.each(data, function (i, v) {
                    $subEspecialidad.append('<option value="' + v.id + '">' + v.nombre + '</option>');
                });
            } else {
                $subEspecialidad.html('<option value="0">No Aplica</option>');
                $subEspecialidad.val(0);
            }
        } else {
            alert('No se pudieron cargar las sub-especialidades');
        }
    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
        console.log(jqXHR, ajaxOptions, thrownError);
    });
}

/* ─── Cargar subtipos de institución ─── */
function dame_subtipos_institucion() {
    var tipoInstitucionId = $('#tipo_institucion').val();

    if(tipoInstitucionId == 3){
        $('#div_subtipo_institucion').removeClass('d-none').show();
    } else {
        $('#div_subtipo_institucion').addClass('d-none').hide();
    }

    // Si no hay selección, no hacer nada
    if (!tipoInstitucionId || tipoInstitucionId == '0') {
        return;
    }

    $.ajax({
        url: "{{ route('home.buscar_subtipo_institucion') }}",
        type: 'get',
        data: { tipo_institucion_id: tipoInstitucionId },
        dataType: 'json'
    })
    .done(function (data) {
        console.log('Subtipos recibidos:', data);

        // Aquí puedes hacer lo que necesites con los datos
        // Por ejemplo, guardarlos en un campo oculto o mostrarlos en otro select

        if (data != null && Array.isArray(data)) {
            // Si necesitas llenar otro select, hazlo aquí
             var $selectSubtipo = $('#subtipo_institucion');
             $selectSubtipo.html('<option value="">Seleccione</option>');
             $.each(data, function (i, v) {
                 $selectSubtipo.append('<option value="' + v.id + '">' + v.nombre + '</option>');
             });
        } else {
            console.log('No se pudieron cargar los subtipos de institución');
        }
    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
        console.log('Error:', jqXHR, ajaxOptions, thrownError);
    });
}
</script>

@if(session('validation_errors') || session('tipo') === 'danger' || (session('error') && old('rol_id')))
<script>
(function () {
    var errors = @json(session('validation_errors', []));
    var rolId  = "{{ old('rol_id') }}";
    if (rolId && window.restoreWizardState) {
        window.restoreWizardState(rolId, errors);
    }
})();
</script>
@endif

@endsection
