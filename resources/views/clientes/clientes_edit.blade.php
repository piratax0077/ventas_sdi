@extends("maestra")
@section("titulo", "Editar cliente")
@section("contenido")

@php
    $rolNombre = strtolower(optional($cliente->rol)->name ?? '');
    if      (str_contains($rolNombre, 'paciente'))    $rolTipo = 'paciente';
    elseif  (str_contains($rolNombre, 'profesional')) $rolTipo = 'profesional';
    elseif  (str_contains($rolNombre, 'asistente'))   $rolTipo = 'asistente';
    elseif  (str_contains($rolNombre, 'instit'))      $rolTipo = 'institucion';
    else                                              $rolTipo = 'paciente';
@endphp

<style>
    .edit-card { max-width:700px; margin:0 auto; border-radius:.5rem; box-shadow:0 4px 18px rgba(0,0,0,.12); }
    .edit-card .card-top { background:#343a40; color:#fff; padding:1.1rem 1.5rem .8rem; border-radius:.5rem .5rem 0 0; text-align:center; }
    .edit-card .card-top h5 { margin-bottom:.2rem; font-size:1.15rem; }
    .edit-card .card-top p  { margin:0; font-size:.85rem; opacity:.8; }
    .section-title { font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6c757d; margin:1.2rem 0 .6rem; border-bottom:1px solid #dee2e6; padding-bottom:.3rem; }
    .floating-label { font-size:.83rem; font-weight:500; color:#495057; margin-bottom:.25rem; }
    .rol-badge { display:inline-block; background:#e8f5e9; border:1px solid #a5d6a7; color:#2e7d32; border-radius:2rem; padding:.15rem .75rem; font-size:.8rem; font-weight:600; margin-bottom:1rem; }
</style>

<div class="edit-card card border-0">
    <div class="card-top">
        <h5>Editar cliente</h5>
        <p>Modifique los datos y guarde los cambios</p>
    </div>

    <div class="card-body px-4 pb-4 pt-3">

        <div>
            <span class="rol-badge">
                @if($rolTipo === 'paciente')        &#128100; Paciente
                @elseif($rolTipo === 'profesional') &#128203; Profesional
                @elseif($rolTipo === 'institucion') &#127968; Institución
                @else                              &#129309; Asistente
                @endif
            </span>
        </div>

        <form method="POST" action="{{ route('clientes.update', [$cliente]) }}" novalidate>
            @method('PUT')
            @csrf
            <input type="hidden" name="rol_id" value="{{ $cliente->rol_id }}">

            {{-- ══ PACIENTE / PROFESIONAL / ASISTENTE ══ --}}
            @if(in_array($rolTipo, ['paciente', 'profesional', 'asistente']))

                <p class="section-title">Información personal</p>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Rut</label>
                        <input type="text" class="form-control form-control-sm" name="rut"
                               autocomplete="off" placeholder="12345678-9"
                               value="{{ old('rut', $cliente->rut) }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Nombre</label>
                        <input type="text" class="form-control form-control-sm" name="nombre"
                               autocomplete="off" value="{{ old('nombre', $cliente->nombre) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Primer apellido</label>
                        <input type="text" class="form-control form-control-sm" name="primer_apellido"
                               autocomplete="off" value="{{ old('primer_apellido', $cliente->primer_apellido) }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Segundo apellido</label>
                        <input type="text" class="form-control form-control-sm" name="segundo_apellido"
                               autocomplete="off" value="{{ old('segundo_apellido', $cliente->segundo_apellido) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label">Nacimiento</label>
                        <input type="date" class="form-control form-control-sm" name="fecha_nacimiento"
                               value="{{ old('fecha_nacimiento', optional($cliente->fecha_nacimiento)->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label">Sexo</label>
                        <select class="form-control form-control-sm" name="sexo">
                            <option value="">Seleccione</option>
                            <option value="M" {{ old('sexo', $cliente->sexo) === 'M' ? 'selected' : '' }}>Hombre</option>
                            <option value="F" {{ old('sexo', $cliente->sexo) === 'F' ? 'selected' : '' }}>Mujer</option>
                        </select>
                    </div>
                    @if($rolTipo === 'paciente')
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label">Previsión</label>
                        <select class="form-control form-control-sm" name="prevision_id">
                            <option value="">Seleccione</option>
                            @foreach($previsiones as $prev)
                                <option value="{{ $prev->id }}"
                                    {{ old('prevision_id', $cliente->prevision_id) == $prev->id ? 'selected' : '' }}>
                                    {{ $prev->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                @if($rolTipo === 'profesional')
                <p class="section-title">Datos profesionales</p>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label">Profesión</label>
                        <select class="form-control form-control-sm" name="profesion" id="profesion"
                                onchange="buscarTipoEspecialidad()">
                            <option value="">Seleccione</option>
                            @foreach($especialidades as $esp)
                                <option value="{{ $esp->id }}"
                                    {{ old('profesion', $cliente->profesion) == $esp->id ? 'selected' : '' }}>
                                    {{ $esp->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label">Especialidad</label>
                        <select class="form-control form-control-sm" name="especialidad" id="especialidad"
                                onchange="buscarSubTipoEspecialidad()">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label">Sub-especialidad</label>
                        <select class="form-control form-control-sm" name="sub_tipo_especialidad" id="sub_tipo_especialidad">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                </div>
                @endif

                <p class="section-title">Residencia y contacto</p>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-8">
                        <label class="floating-label">Dirección</label>
                        <input type="text" class="form-control form-control-sm" name="direccion"
                               autocomplete="off" value="{{ old('direccion', $cliente->direccion) }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label">Nº</label>
                        <input type="number" class="form-control form-control-sm" name="numero_dir"
                               autocomplete="off" value="{{ old('numero_dir', $cliente->numero_dir) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Región</label>
                        <select class="form-control form-control-sm" name="region_id" id="region_id"
                                onchange="buscarCiudades(this.value, null)">
                            <option value="">Seleccione</option>
                            @foreach($regiones as $region)
                                <option value="{{ $region->id }}"
                                    {{ old('region_id', $cliente->region_id) == $region->id ? 'selected' : '' }}>
                                    {{ $region->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Comuna</label>
                        <select class="form-control form-control-sm" name="ciudad_id" id="ciudad_id">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Celular</label>
                        <input type="tel" class="form-control form-control-sm" name="celular"
                               autocomplete="off" placeholder="+569..."
                               value="{{ old('celular', $cliente->celular) }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Teléfono</label>
                        <input type="tel" class="form-control form-control-sm" name="telefono"
                               autocomplete="off" placeholder="+562..."
                               value="{{ old('telefono', $cliente->telefono) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Correo electrónico</label>
                        <input type="email" class="form-control form-control-sm" name="correo"
                               autocomplete="off" value="{{ old('correo', $cliente->correo) }}">
                    </div>
                </div>

            @elseif($rolTipo === 'institucion')

                <p class="section-title">Datos de la institución</p>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Rut institución</label>
                        <input type="text" class="form-control form-control-sm" name="inst_rut"
                               autocomplete="off" placeholder="12345678-9"
                               value="{{ old('inst_rut', $cliente->inst_rut) }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Nombre institución</label>
                        <input type="text" class="form-control form-control-sm" name="nombre_institucion"
                               autocomplete="off" value="{{ old('nombre_institucion', $cliente->nombre_institucion) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Tipo de institución</label>
                        <input type="text" class="form-control form-control-sm" name="tipo_institucion"
                               autocomplete="off" placeholder="Ej: Clínica, Hospital..."
                               value="{{ old('tipo_institucion', $cliente->tipo_institucion) }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Teléfono institución</label>
                        <input type="tel" class="form-control form-control-sm" name="inst_telefono"
                               autocomplete="off" placeholder="+562..."
                               value="{{ old('inst_telefono', $cliente->inst_telefono) }}">
                    </div>
                </div>

                <p class="section-title">Dirección institución</p>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-8">
                        <label class="floating-label">Dirección</label>
                        <input type="text" class="form-control form-control-sm" name="inst_direccion"
                               autocomplete="off" value="{{ old('inst_direccion', $cliente->inst_direccion) }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label class="floating-label">Nº</label>
                        <input type="number" class="form-control form-control-sm" name="inst_numero_dir"
                               autocomplete="off" value="{{ old('inst_numero_dir', $cliente->inst_numero_dir) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Región</label>
                        <select class="form-control form-control-sm" name="inst_region_id" id="inst_region_id"
                                onchange="buscarCiudadesInst(this.value, null)">
                            <option value="">Seleccione</option>
                            @foreach($regiones as $region)
                                <option value="{{ $region->id }}"
                                    {{ old('inst_region_id', $cliente->inst_region_id) == $region->id ? 'selected' : '' }}>
                                    {{ $region->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Comuna</label>
                        <select class="form-control form-control-sm" name="inst_ciudad_id" id="inst_ciudad_id">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                </div>

                <p class="section-title">Datos del responsable</p>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Rut responsable</label>
                        <input type="text" class="form-control form-control-sm" name="responsable_rut"
                               autocomplete="off" placeholder="12345678-9"
                               value="{{ old('responsable_rut', $cliente->responsable_rut) }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Nombre responsable</label>
                        <input type="text" class="form-control form-control-sm" name="responsable_nombre"
                               autocomplete="off" value="{{ old('responsable_nombre', $cliente->responsable_nombre) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Primer apellido</label>
                        <input type="text" class="form-control form-control-sm" name="responsable_apellido_uno"
                               autocomplete="off" value="{{ old('responsable_apellido_uno', $cliente->responsable_apellido_uno) }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Segundo apellido</label>
                        <input type="text" class="form-control form-control-sm" name="responsable_apellido_dos"
                               autocomplete="off" value="{{ old('responsable_apellido_dos', $cliente->responsable_apellido_dos) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Celular</label>
                        <input type="tel" class="form-control form-control-sm" name="responsable_celular"
                               autocomplete="off" placeholder="+569..."
                               value="{{ old('responsable_celular', $cliente->responsable_celular) }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Teléfono</label>
                        <input type="tel" class="form-control form-control-sm" name="responsable_telefono"
                               autocomplete="off" placeholder="+562..."
                               value="{{ old('responsable_telefono', $cliente->responsable_telefono) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label class="floating-label">Correo electrónico</label>
                        <input type="email" class="form-control form-control-sm" name="correo"
                               autocomplete="off" value="{{ old('correo', $cliente->correo) }}">
                    </div>
                </div>

            @endif

            @include("notificacion")

            <div class="d-flex justify-content-between mt-3">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('clientes.index') }}">&lsaquo; Volver</a>
                <button type="submit" class="btn btn-success btn-sm">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    @if($rolTipo === 'institucion')
        buscarCiudadesInst({{ $cliente->inst_region_id ?? 'null' }}, {{ $cliente->inst_ciudad_id ?? 'null' }});
    @else
        buscarCiudades({{ $cliente->region_id ?? 'null' }}, {{ $cliente->ciudad_id ?? 'null' }});
    @endif

    @if($rolTipo === 'profesional')
        if ({{ $cliente->profesion ?? 'null' }}) {
            cargarEspecialidadesEdit(
                {{ $cliente->profesion ?? 'null' }},
                {{ $cliente->especialidad ?? 'null' }},
                {{ $cliente->sub_tipo_especialidad ?? 'null' }}
            );
        }
    @endif
})();

function buscarCiudades(regionId, preselect) {
    var sel = document.getElementById('ciudad_id');
    if (!sel || !regionId) return;
    sel.innerHTML = '<option value="">Seleccione</option>';
    $.ajax({ url: "{{ route('home.buscar_ciudad_region') }}", type: 'get', data: { region: regionId }, dataType: 'json' })
    .done(function (data) {
        if (!data) return;
        data.forEach(function (c) {
            var o = document.createElement('option');
            o.value = c.id; o.textContent = c.nombre;
            if (preselect && c.id == preselect) o.selected = true;
            sel.appendChild(o);
        });
    });
}

function buscarCiudadesInst(regionId, preselect) {
    var sel = document.getElementById('inst_ciudad_id');
    if (!sel || !regionId) return;
    sel.innerHTML = '<option value="">Seleccione</option>';
    $.ajax({ url: "{{ route('home.buscar_ciudad_region') }}", type: 'get', data: { region: regionId }, dataType: 'json' })
    .done(function (data) {
        if (!data) return;
        data.forEach(function (c) {
            var o = document.createElement('option');
            o.value = c.id; o.textContent = c.nombre;
            if (preselect && c.id == preselect) o.selected = true;
            sel.appendChild(o);
        });
    });
}

@if($rolTipo === 'profesional')
function cargarEspecialidadesEdit(profId, preEsp, preSub) {
    $.ajax({ url: "{{ route('home.buscar_especialidad') }}", type: 'get', data: { especialidad: profId } })
    .done(function (data) {
        if (!data) return;
        data = JSON.parse(data);
        var sel = $('#especialidad');
        sel.html('<option value="">Seleccione</option>');
        $.each(data, function (i, v) {
            sel.append('<option value="'+v.id+'"'+(preEsp==v.id?' selected':'')+'>'+v.nombre+'</option>');
        });
        if (data.length === 0) { sel.html('<option value="0">No Aplica</option>'); sel.val(0); }
        if (preEsp) cargarSubEspecialidadesEdit(preEsp, preSub);
    });
}
function cargarSubEspecialidadesEdit(espId, preSub) {
    $.ajax({ url: "{{ route('home.buscar_sub_tipo_especialidad') }}", type: 'get', data: { especialidad: espId } })
    .done(function (data) {
        if (!data) return;
        data = JSON.parse(data);
        var sel = $('#sub_tipo_especialidad');
        sel.html('<option value="">Seleccione</option>');
        $.each(data, function (i, v) {
            sel.append('<option value="'+v.id+'"'+(preSub==v.id?' selected':'')+'>'+v.nombre+'</option>');
        });
        if (data.length === 0) { sel.html('<option value="0">No Aplica</option>'); sel.val(0); }
    });
}
function buscarTipoEspecialidad() { cargarEspecialidadesEdit($('#profesion').val(), null, null); }
function buscarSubTipoEspecialidad() { cargarSubEspecialidadesEdit($('#especialidad').val(), null); }
@endif
</script>

@endsection
