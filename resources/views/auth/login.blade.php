@extends('maestra')
@section("titulo", "Iniciar Sesión")
@section('contenido')
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5 col-lg-4">

            <div class="text-center mb-4">
                <img src="{{ url('/img/logo_pais_vertical.png') }}" alt="{{ env('APP_NAME') }}"
                     class="img-fluid mb-3" style="max-height:90px;">
                <h3 class="fw-bold mt-5">Iniciar Sesión</h3>
                <p class="text-muted small">{{ env('APP_NAME') }}</p>
            </div>

            <div class="card shadow border-0">
                <div class="card-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                            <i class="fa fa-exclamation-circle"></i>
                            <strong>{{ $errors->first() }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                Correo electrónico
                            </label>
                            <input id="email" type="email"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}"
                                   placeholder="correo@ejemplo.com"
                                   required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                Contraseña
                            </label>
                            <div class="input-group">
                                <input id="password" type="password"
                                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                                       name="password" placeholder="••••••••"
                                       required autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                                    <i class="fa fa-eye" id="toggleIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember"
                                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted small" for="remember">
                                    Recordarme
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="small text-decoration-none" href="{{ route('password.request') }}">
                                    ¿Olvidó su contraseña?
                                </a>
                            @endif
                        </div>

                        <div class="d-grid text-center">
                            <button type="submit" class="btn btn-info btn-lg">
                                <i class="fa fa-sign-in-alt me-1"></i> Ingresar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    </script>
@endsection

