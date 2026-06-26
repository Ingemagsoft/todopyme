{{--
    Layout para pantallas de autenticación (login, recuperar contraseña).
    No incluye sidebar ni topbar — solo el formulario centrado,
    usando la clase auth-bg ya definida en pages/_authentication.scss
    (parte de app.scss, trasplantado en la Etapa 0 de este proyecto).
    Internamente extiende layouts.base de Adminto.
--}}
@extends('layouts.base', ['title' => $title ?? 'Iniciar sesión'])

@section('content')
    <div class="auth-bg d-flex min-vh-100">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xxl-3 col-lg-5 col-md-6">
                @yield('auth-content')
            </div>
        </div>
    </div>
@endsection