@extends('layouts.auth', ['title' => 'todopyme — Iniciar sesión'])

@section('auth-content')

    {{-- Logo IMS Global --}}
    <a class="auth-brand d-flex justify-content-center mb-2" href="{{ route('login') }}">
        <img alt="IMS Global" height="76" src="{{ asset('img/logo_slogan_color_2.png') }}" />
    </a>
    <!-- <p class="fw-semibold mb-4 text-center text-muted fs-15">Sistema de Gestión Administrativa</p> -->

    <div class="card overflow-hidden text-center p-xxl-4 p-3 mb-0">

        <h4 class="fw-semibold mb-3 fs-18">Inicia sesión en tu cuenta</h4>

        {{-- Errores generales (ej: empresa no registrada) --}}
        @if ($errors->has('login'))
            <div class="alert alert-danger text-start">
                {{ $errors->first('login') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="text-start mb-3">
            @csrf

            {{-- Código de empresa --}}
            <div class="mb-3">
                <label class="form-label" for="codigo">Código de empresa</label>
                <input
                    type="text"
                    id="codigo"
                    name="codigo"
                    class="form-control text-uppercase @error('codigo') is-invalid @enderror"
                    placeholder="Codigo de empresa (ej: DEMO)"
                    value="{{ old('codigo') }}"
                    maxlength="20"
                    autocomplete="off"
                    style="text-transform: uppercase;"
                />
                @error('codigo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nombre de usuario --}}
            <div class="mb-3">
                <label class="form-label" for="nomusuario">Usuario</label>
                <input
                    type="text"
                    id="nomusuario"
                    name="nomusuario"
                    class="form-control @error('nomusuario') is-invalid @enderror"
                    placeholder="Tu nombre de usuario"
                    value="{{ old('nomusuario') }}"
                    autocomplete="off"
                />
                @error('nomusuario')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Contraseña --}}
            <div class="mb-3">
                <label class="form-label" for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="••••••••"
                />
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Botón --}}
            <div class="d-grid">
                <button class="btn btn-primary fw-semibold" type="submit">
                    Ingresar al sistema
                </button>
            </div>
        </form>

        <!-- <p class="text-muted fs-14 mb-0">
            ¿Problemas para ingresar?
            <a class="fw-semibold ms-1" href="mailto:soporte@imsglobal.co">soporte@imsglobal.co</a>
        </p> -->
    </div>

    <p class="mt-4 text-center mb-0">
        Ingeniería y Marketing de Software Global SAS
    </p>

@endsection