@extends('layouts.app', ['title' => 'Clientes — todopyme'])

@section('page-content')

    <div class="row">
        <div class="col-12">

            {{-- Encabezado de página: título + botón de acción --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-semibold mb-0">Clientes</h4>
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                    <i class="ri-add-line align-middle me-1"></i> Nuevo cliente
                </a>
            </div>

            {{-- Mensaje de éxito (creado/actualizado/desactivado) --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">

                    {{-- Formulario de búsqueda progresiva --}}
                    <form method="GET" action="{{ route('clientes.index') }}" class="row g-2 mb-3">
                        <div class="col-md-6">
                            <input
                                type="text"
                                name="busqueda"
                                class="form-control"
                                placeholder="Buscar por código, nombre o documento..."
                                value="{{ $busqueda }}"
                                autofocus
                            />
                        </div>
                        <div class="col-md-3">
                            <div class="form-check mt-2">
                                <input
                                    type="checkbox"
                                    name="inactivos"
                                    value="1"
                                    class="form-check-input"
                                    id="checkInactivos"
                                    {{ $inactivos ? 'checked' : '' }}
                                    onchange="this.form.submit()"
                                />
                                <label class="form-check-label" for="checkInactivos">
                                    Incluir inactivos
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="ri-search-line align-middle"></i> Buscar
                            </button>
                            @if ($busqueda !== '')
                                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>

                    {{-- Tabla de resultados --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre / Razón social</th>
                                    <th>Documento</th>
                                    <th>Ciudad</th>
                                    <th>Teléfono</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clientes as $cliente)
                                    <tr>
                                        <td class="fw-semibold">{{ $cliente->codigo }}</td>
                                        <td>{{ $cliente->nombre_completo }}</td>
                                        <td>{{ $cliente->documento_formateado }}</td>
                                        <td>{{ $cliente->ciudad->nombre ?? '—' }}</td>
                                        <td>{{ $cliente->telefono ?? $cliente->celular ?? '—' }}</td>
                                        <td class="text-center">
                                            @if ($cliente->activo)
                                                <span class="badge bg-success-subtle text-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('clientes.edit', $cliente->id) }}"
                                                class="btn btn-sm btn-soft-primary"
                                                data-bs-toggle="tooltip" title="Editar">
                                                <i class="ri-pencil-line"></i>
                                            </a>

                                            @if ($cliente->activo)
                                                <form action="{{ route('clientes.destroy', $cliente->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('¿Desactivar este cliente?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-soft-danger"
                                                        data-bs-toggle="tooltip" title="Desactivar">
                                                        <i class="ri-forbid-line"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('clientes.reactivar', $cliente->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-soft-success"
                                                        data-bs-toggle="tooltip" title="Reactivar">
                                                        <i class="ri-check-line"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            @if ($busqueda !== '')
                                                No se encontraron clientes para "{{ $busqueda }}".
                                            @else
                                                Aún no hay clientes registrados.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    @if ($clientes instanceof \Illuminate\Pagination\LengthAwarePaginator && $clientes->hasPages())
                        <div class="mt-3">
                            {{ $clientes->links() }}
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

@endsection