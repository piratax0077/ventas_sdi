@extends('maestra')
@section("titulo", "Mi Perfil")
@section('contenido')
    <div class="row">
        <div class="col-12">
            {{-- Header profesional --}}

                <div class="py-4 d-flex align-items-center justify-content-between">
                    <div class="text-dark">
                        <h2 class="mb-1 fw-bold">
                            
                            Mi Perfil
                        </h2>
                        <p class="mb-0 opacity-75"> Actualiza tu información personal y contraseña</p>
                    </div>
                </div>
     

            @include("notificacion")

            <div class="row g-4">
                {{-- Información del Usuario --}}
                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 fw-semibold">
                              <i class="fa fa-user-circle text-primary me-2"></i>  Editar Datos Personales
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('perfil.update') }}">
                                @csrf
                                @method('PUT')

                                {{-- Nombre --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        Nombre Completo
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', Auth::user()->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                       Correo Electrónico
                                    </label>
                                    <input type="email"
                                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', Auth::user()->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Separador --}}
                                <hr class="my-4">

                                {{-- Cambiar Contraseña --}}
                                <h5 class="fw-semibold mb-3">
                                    <i class="fa fa-lock me-2 text-primary"></i> Cambiar Contraseña
                                </h5>

                                {{-- Contraseña Actual --}}
                                <div class="mb-3">
                                    <label for="current_password" class="form-label fw-semibold">
                                        Contraseña Actual
                                    </label>
                                    <input type="password"
                                           class="form-control form-control-lg @error('current_password') is-invalid @enderror"
                                           id="current_password"
                                           name="current_password"
                                           placeholder="Dejar en blanco si no deseas cambiar">
                                    @error('current_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Nueva Contraseña --}}
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">
                                        Nueva Contraseña
                                    </label>
                                    <input type="password"
                                           class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Dejar en blanco si no deseas cambiar">
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Confirmar Nueva Contraseña --}}
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-semibold">
                                        Confirmar Nueva Contraseña
                                    </label>
                                    <input type="password"
                                           class="form-control form-control-lg"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Dejar en blanco si no deseas cambiar">
                                </div>

                                {{-- Botones de Acción --}}
                                <div class="d-flex gap-2 float-right">
                                    <button type="submit" class="btn btn-info btn-lg mr-2">
                                        <i class="fa fa-save me-2"></i> Guardar Cambios
                                    </button>
                                    <a href="{{ route('home') }}" class="btn btn-secondary btn-lg">
                                        <i class="fa fa-arrow-left me-2"></i>  Volver
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Card de Información --}}
                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm bg-light">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa fa-info-circle me-2 text-primary"></i> Información de la Cuenta
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Avatar --}}
                            <div class="text-center mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center"
                                     style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 2.5rem;">
                                    <i class="fa fa-user"></i>
                                </div>
                            </div>

                            {{-- Detalles --}}
                            <div class="mb-3">
                                <p class="text-muted small mb-1">USUARIO</p>
                                <p class="fw-semibold">{{ Auth::user()->name }}</p>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted small mb-1">CORREO</p>
                                <p class="fw-semibold">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted small mb-1">MIEMBRO DESDE</p>
                                <p class="fw-semibold">
                                    {{ Auth::user()->created_at->format('d/m/Y') }}
                                </p>
                            </div>

                            <hr>

                            <div class="alert alert-blue-ligth mb-0" role="alert">
                                <small>
                                    <i class="fa fa-lightbulb me-1"></i>
                                     Mantén tu contraseña segura y actualiza regularmente tus datos de contacto.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Card de Seguridad --}}
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fa fa-shield-alt me-2 text-primary"></i> Seguridad
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <i class="fa fa-check-circle text-success me-2"></i>
                                <small class="text-muted">Sesión activa</small>
                            </p>
                            <p class="mb-0">
                                <i class="fa fa-clock text-warning me-2"></i>
                                <small class="text-muted">Última actualización: {{ Auth::user()->updated_at->diffForHumans() }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .form-control-lg {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .form-control-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-lg:hover {
        transform: translateY(-2px);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
        background: linear-gradient(135deg, #f5f7fa 0%, #f1f3f5 100%);
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.25rem;
        }

        .btn-lg {
            width: 100%;
        }
    }
</style>
@endpush
