
@extends("maestra")
@section("titulo", "Centro de Soporte")
@section("contenido")
    <div class="row">
        <div class="col-12">
            {{-- Header --}}
            <div class="mb-4 gradient-header">
                <div class=" py-4 d-flex align-items-center justify-content-between">
                    <div class="text-dark">
                        <h2 class="mb-1 fw-bold">
                            Centro de Soporte
                        </h2>
                        <p class="mb-0 opacity-75">Estamos aquí para ayudarte. Describe tu problema y te asistiremos pronto</p>
                    </div>
                </div>
            </div>

            @include("notificacion")

            <div class="row">
                <div class="col-lg-8">
                    {{-- Formulario de Solicitud --}}
                    <div class="card border-0 shadow-sm mb-4 p-1">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 fw-semibold">
                                Nueva Solicitud de Soporte
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('soporte.store') }}" method="POST" id="formSoporte" enctype="multipart/form-data">
                                @csrf

                                {{-- Tipo de Solicitud --}}
                                <div class="mb-4">
                                    <label for="tipo_solicitud" class="form-label fw-semibold">
                                        Tipo de Solicitud
                                    </label>
                                    <select name="tipo_solicitud" id="tipo_solicitud" class="form-select form-select-lg" required>
                                        <option value="">-- Seleccione un tipo --</option>
                                        <option value="problema_tecnico"><i class="fa fa-bug"></i> Problema Técnico</option>
                                        <option value="consulta_general"><i class="fa fa-question-circle"></i> Consulta General</option>
                                        <option value="reporte_error"><i class="fa fa-exclamation-triangle"></i> Reporte de Error</option>
                                        <option value="solicitud_feature"><i class="fa fa-lightbulb"></i> Solicitud de Mejora</option>
                                        <option value="acceso_cuenta"><i class="fa fa-lock"></i> Problema de Acceso</option>
                                        <option value="facturacion"><i class="fa fa-receipt"></i> Facturación</option>
                                        <option value="otros"><i class="fa fa-ellipsis-h"></i> Otros</option>
                                    </select>
                                    @error('tipo_solicitud')
                                        <div class="alert alert-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Asunto --}}
                                <div class="mb-4">
                                    <label for="asunto" class="form-label fw-semibold">
                                        Asunto
                                    </label>
                                    <input type="text" name="asunto" id="asunto" class="form-control form-control-lg" placeholder="Resumen breve del problema o consulta" required maxlength="200" value="{{ old('asunto') }}">
                                    @error('asunto')
                                        <div class="alert alert-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Descripción --}}
                                <div class="mb-4">
                                    <label for="descripcion" class="form-label fw-semibold">
                                        Descripción Detallada
                                    </label>
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="6" placeholder="Describe tu problema o consulta lo máximo posible para que podamos ayudarte mejor..." required>{{ old('descripcion') }}</textarea>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fa fa-info-circle me-1"></i> Incluye pasos para reproducir el problema si es aplicable
                                    </small>
                                    @error('descripcion')
                                        <div class="alert alert-danger small mt-2"> {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Prioridad --}}
                                <div class="mb-4">
                                    <label for="prioridad" class="form-label fw-semibold">
                                        Prioridad
                                    </label>
                                    <select name="prioridad" id="prioridad" class="form-select form-select-lg">
                                        <option value="baja">Baja</option>
                                        <option value="media" selected>Media</option>
                                        <option value="alta">Alta</option>
                                        <option value="urgente">Urgente</option>
                                    </select>
                                </div>

                                {{-- Dropzone para archivos --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fa fa-paperclip me-1"></i> Archivos Adjuntos (Opcional)
                                    </label>
                                    <div id="dropzoneContainer" class="dropzone dropzone-custom">
                                        <div class="dz-message" data-dz-message>
                                            <div style="font-size: 2.5rem; margin-bottom: 1rem;">
                                                <i class="fa fa-cloud-upload-alt text-primary"></i>
                                            </div>
                                            <h5>Arrastra archivos aquí o haz clic para seleccionar</h5>
                                            <p class="text-muted">Puedes agregar capturas de pantalla, documentos, etc.</p>
                                            <small class="text-muted d-block mt-2">Máximo 5 archivos, 5MB cada uno</small>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fa fa-info-circle me-1"></i> Formatos permitidos: JPG, PNG, PDF, DOC, DOCX (máx. 5MB por archivo)
                                    </small>
                                </div>

                                {{-- Inputs hidden para archivos subidos por Dropzone --}}
                                <div id="archivosSubidos"></div>

                                {{-- Botones --}}
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('home') }}" class="btn mr-2 btn-danger btn-lg">
                                        <i class="fa fa-times me-2"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-info btn-lg">
                                        <i class="fa fa-paper-plane me-2"></i> Enviar Solicitud
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Sidebar de Información --}}
                <div class="col-lg-4">
                    {{-- Información de Contacto --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="mb-0 fw-semibold">
                                Contacto Directo
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="text-muted small mb-1"> Correo Electrónico:</p>
                                <p class="fw-semibold mb-0">
                                    <i class="fa fa-envelope me-2 text-primary"></i>
                                    <a href="mailto:contacto@medsdi.cl" class="text-decoration-none text-secondary"> contacto@medsdi.cl</a>
                                </p>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted small mb-1"> Teléfono:</p>
                                <p class="fw-semibold mb-0">
                                    <i class="fa fa-phone me-2 text-primary"></i>
                                    <a href="tel:+56912345678" class="text-decoration-none text-secondary"> +56 9 1234 5678</a>
                                </p>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted small mb-1"> Horario de Atención:</p>
                                <p class="fw-semibold mb-0">
                                    <i class="fa fa-clock me-2 text-primary"></i>
                                    Lunes a Viernes, 09:00 - 17:00
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- FAQ Rápido --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fa fa-question me-2"></i> Preguntas Frecuentes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed p-0" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                            <i class="fa fa-chevron-right me-2 text-primary"></i>¿Cuánto tarda la respuesta?
                                        </button>
                                    </h2>
                                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body p-3">
                                            Las solicitudes se responden dentro de 24-48 horas hábiles.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed p-0" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                            <i class="fa fa-chevron-right me-2 text-primary"></i>¿Puedo adjuntar archivos?
                                        </button>
                                    </h2>
                                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body p-3">
                                            Sí, puedes adjuntar hasta 5 archivos de hasta 5MB cada uno.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed p-0" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                            <i class="fa fa-chevron-right me-2 text-primary"></i>¿Cómo hago seguimiento?
                                        </button>
                                    </h2>
                                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body p-3">
                                            Recibirás un número de ticket en tu correo para hacer seguimiento.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Deshabilitar autodescubrimiento ANTES del ready
        Dropzone.autoDiscover = false;

        $(document).ready(function () {
            // Select2
            $('#tipo_solicitud').select2({
                placeholder: 'Seleccione un tipo de solicitud',
                allowClear: true,
                width: '100%'
            });

            $('#prioridad').select2({
                width: '100%'
            });

            // Inicializar Dropzone v5
            const myDropzone = new Dropzone('#dropzoneContainer', {
                url: '{{ route("soporte.upload") }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                maxFiles: 5,
                maxFilesize: 5,
                acceptedFiles: '.jpg,.jpeg,.png,.pdf,.doc,.docx',
                addRemoveLinks: true,
                clickable: true,
                autoProcessQueue: false,
                paramName: 'file',
                dictDefaultMessage: 'Arrastra archivos aquí o haz clic para seleccionar',
                dictMaxFilesExceeded: 'No puedes subir más de 5 archivos',
                dictFileTooBig: 'El archivo es demasiado grande (máx. 5MB)',
                dictInvalidFileType: 'Solo se aceptan: JPG, PNG, PDF, DOC, DOCX',
                dictRemoveFile: 'Eliminar',
                dictCancelUpload: 'Cancelar',
                init: function() {
                    const dz = this;

                    dz.on('error', function(file, message) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en archivo',
                            text: typeof message === 'string' ? message : message.message || 'Error desconocido'
                        });
                        dz.removeFile(file);
                    });

                    // Al subir exitosamente: guardar metadata en inputs hidden
                    dz.on('success', function(file, response) {
                        if (response && response.file) {
                            const f = response.file;
                            const container = document.getElementById('archivosSubidos');
                            const idx = dz.files.indexOf(file);
                            container.insertAdjacentHTML('beforeend',
                                '<input type="hidden" name="archivos[' + idx + '][nombre_original]" value="' + f.nombre_original + '">'
                                + '<input type="hidden" name="archivos[' + idx + '][nombre_guardado]" value="' + f.nombre_guardado + '">'
                                + '<input type="hidden" name="archivos[' + idx + '][ruta]" value="' + f.ruta + '">'
                                + '<input type="hidden" name="archivos[' + idx + '][tipo_mime]" value="' + f.tipo_mime + '">'
                                + '<input type="hidden" name="archivos[' + idx + '][tamano]" value="' + f.tamano + '">'
                            );
                        }
                    });

                    // Al eliminar un archivo de Dropzone, limpiar su hidden input
                    dz.on('removedfile', function(file) {
                        const idx = dz.files.indexOf(file);
                        document.querySelectorAll('[name^="archivos[' + idx + ']"]').forEach(el => el.remove());
                    });

                    // Procesar archivos al enviar formulario
                    let enviandoFormulario = false;
                    document.getElementById('formSoporte').addEventListener('submit', function(e) {
                        e.preventDefault();

                        if (enviandoFormulario) return;

                        const pendientes = dz.getQueuedFiles();

                        if (pendientes.length === 0) {
                            this.submit();
                            return;
                        }

                        enviandoFormulario = true;
                        dz.on('queuecomplete', function() {
                            document.getElementById('formSoporte').submit();
                        });
                        dz.processQueue();
                    });
                }
            });
        });
    </script>
    @endpush
    @push('styles')
    <style>
        /* Estilos del Dropzone */
        .dropzone-custom {
            border: 2px dashed #667eea;
            border-radius: 0.5rem;
            padding: 3rem 1rem;
            transition: all 0.3s ease;
            background-color: rgba(102, 126, 234, 0.05);
            cursor: pointer;
        }

        .dropzone-custom:hover {
            border-color: #764ba2;
            background-color: rgba(102, 126, 234, 0.1);
        }

        .dz-message {
            text-align: center;
            color: #667eea;
        }

        .dz-message h5 {
            font-weight: 600;
            color: #333;
        }

        .dz-preview {
            display: block;
        }

        /* Estilos del formulario */
        .form-select-lg,
        .form-control-lg {
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-select-lg:focus,
        .form-control-lg:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        textarea.form-control {
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            resize: vertical;
        }

        textarea.form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Cards */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
        }

        /* Accordion */
        .accordion-button:not(.collapsed) {
            background-color: rgba(102, 126, 234, 0.1);
            color: #667eea;
            font-weight: 600;
        }

        .accordion-button:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }

        /* Botones */
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6bcf 0%, #6a3a92 100%);
        }

        /* Gradiente header */
        .gradient-header {
            background: linear-gradient(135deg, #f5f7fa 0%, #f1f3f5 100%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-select-lg,
            .form-control-lg {
                font-size: 1rem;
            }

            .dropzone-custom {
                padding: 2rem 0.5rem;
            }

            .btn-lg {
                width: 100%;
            }

            .d-md-flex {
                flex-direction: column;
            }
        }
    </style>
    @endpush

@endsection
