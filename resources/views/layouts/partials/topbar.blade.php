{{--
    Topbar — barra superior de todas las pantallas internas.

    Simplificado desde el topbar.blade.php original de Adminto:
    se quitaron notificaciones, búsqueda, selector de idioma y
    apps de demo, ya que no aplican al sistema de IMS Global.

    Accesos rápidos: iconos sueltos con tooltip (data-bs-toggle="tooltip"),
    inicializados automáticamente por app.js — no requiere JS adicional.

    Datos de sesión usados aquí (guardados en AuthController,
    Etapa 1): session('tenant_nombre'), session('user_nombre').
--}}
<header class="app-topbar" id="header">
    <div class="page-container topbar-menu">

        <div class="d-flex align-items-center gap-2">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="logo">
                <span class="logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('img/logo_ims_color.png') }}" alt="IMS Global" height="32">
                    </span>
                </span>
                <span class="logo-dark">
                    <span class="logo-lg">
                        <img src="{{ asset('img/logo_ims_color.png') }}" alt="IMS Global" height="32">
                    </span>
                </span>
            </a>

            {{-- Botón para colapsar/expandir el sidebar --}}
            <button class="sidenav-toggle-button px-2">
                <i class="ri-menu-5-line fs-24"></i>
            </button>

            {{-- Título de la página actual --}}
            <div class="topbar-item d-none d-md-flex px-2">
                <h4 class="page-title fs-18 fw-semibold mb-0">
                    {{ $topbarTitle ?? 'Click-pyme' }}
                </h4>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            {{-- ── Accesos rápidos ─────────────────────────────────── --}}
            {{-- Iconos sueltos con tooltip, mismo patrón visual que light-dark-mode --}}

            <div class="topbar-item d-none d-sm-flex">
                <a href="{{ route('clientes.index') }}" class="topbar-link"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Clientes">
                    <i class="ri-team-line fs-22"></i>
                </a>
            </div>

            <div class="topbar-item d-none d-sm-flex">
                <a href="{{ route('ventas.facturacion') }}" class="topbar-link"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Facturación POS">
                    <i class="ri-shopping-cart-line fs-22"></i>
                </a>
            </div>

            <div class="topbar-item d-none d-sm-flex">
                <a href="javascript:void(0);" class="topbar-link disabled"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="POS (próximamente)">
                    <i class="ri-archive-line fs-22"></i>
                </a>
            </div>

            <div class="topbar-item d-none d-sm-flex">
                <a href="javascript:void(0);" class="topbar-link disabled"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cuadre de turno (próximamente)">
                    <i class="ri-file-list-3-line fs-22"></i>
                </a>
            </div>

            <div class="topbar-item d-none d-sm-flex">
                <a href="javascript:void(0);" class="topbar-link disabled"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cambiar turno (próximamente)">
                    <i class="ri-bar-chart-line fs-22"></i>
                </a>
            </div>

            <div class="topbar-item d-none d-sm-flex">
                <a href="javascript:void(0);" class="topbar-link disabled"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Informe-Z (próximamente)">
                    <i class="ri-settings-3-line fs-22"></i>
                </a>
            </div>

            {{-- ── Fin accesos rápidos ─────────────────────────────── --}}
        </div>

        <div class="d-flex align-items-center gap-2">

            {{-- Empresa activa --}}
            <div class="topbar-item d-none d-sm-flex">
                <span class="topbar-link">
                    <i class="ri-building-line fs-18 me-1 align-middle"></i>
                    <span class="align-middle fw-semibold">{{ session('tenant_nombre') }}</span>
                </span>
            </div>

            {{-- Switch claro/oscuro --}}
            <div class="topbar-item d-none d-sm-flex">
                <button class="topbar-link" id="light-dark-mode" type="button">
                    <i class="ri-moon-line light-mode-icon fs-22"></i>
                    <i class="ri-sun-line dark-mode-icon fs-22"></i>
                </button>
            </div>

            {{-- Usuario --}}
            <div class="topbar-item nav-user">
                <div class="dropdown">
                    <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                        type="button" aria-expanded="false">
                        <i class="ri-account-circle-line fs-24 me-lg-1"></i>
                        <span class="d-lg-flex flex-column gap-1 d-none">
                            <h5 class="my-0">{{ session('user_nombre') }}</h5>
                        </span>
                        <i class="ri-arrow-down-s-line d-none d-lg-block align-middle ms-1"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">{{ session('user_nomusuario') }}</h6>
                        </div>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="ri-logout-box-line me-1 fs-16 align-middle"></i>
                                <span class="align-middle">Cerrar sesión</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>