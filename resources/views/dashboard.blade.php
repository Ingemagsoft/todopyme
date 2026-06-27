@extends('layouts.app', ['title' => 'Dashboard — todopyme'])

@section('page-content')

    {{-- Tarjetas de estadísticas — simples, sin gráficos por ahora --}}
    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1 g-3 mb-3">

        <div class="col">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-md bg-primary-subtle rounded flex-shrink-0">
                            <i class="ri-team-line fs-24 text-primary avatar-title"></i>
                        </div>
                        <div>
                            <h3 class="fw-semibold mb-0">{{ $totalClientes }}</h3>
                            <p class="text-muted mb-0">Clientes registrados</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2">
                    <a href="{{ route('clientes.index') }}" class="link-reset fw-semibold fs-13">
                        Ver clientes <i class="ri-arrow-right-line align-middle"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-md bg-success-subtle rounded flex-shrink-0">
                            <i class="ri-shopping-cart-2-line fs-24 text-success avatar-title"></i>
                        </div>
                        <div>
                            <h3 class="fw-semibold mb-0">0</h3>
                            <p class="text-muted mb-0">Ventas hoy</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2">
                    <span class="text-muted fs-13">Próximamente</span>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-md bg-warning-subtle rounded flex-shrink-0">
                            <i class="ri-archive-2-line fs-24 text-warning avatar-title"></i>
                        </div>
                        <div>
                            <h3 class="fw-semibold mb-0">0</h3>
                            <p class="text-muted mb-0">Productos en inventario</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2">
                    <span class="text-muted fs-13">Próximamente</span>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-md bg-danger-subtle rounded flex-shrink-0">
                            <i class="ri-file-text-line fs-24 text-danger avatar-title"></i>
                        </div>
                        <div>
                            <h3 class="fw-semibold mb-0">0</h3>
                            <p class="text-muted mb-0">Facturas DIAN este mes</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2">
                    <span class="text-muted fs-13">Próximamente</span>
                </div>
            </div>
        </div>

    </div>

@endsection