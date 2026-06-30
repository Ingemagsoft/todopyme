@extends('layouts.app', ['title' => 'Editar cliente — todopyme'])

@section('page-content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="fw-semibold mb-0">Editar cliente</h4>
        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line align-middle"></i> Volver al listado
        </a>
    </div>

    <form method="POST" action="{{ route('clientes.update', $cliente->id) }}">
        @csrf
        @method('PUT')

        {{-- Errores generales --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Por favor corrige los siguientes errores:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ── Card: Datos de identificación ───────────────────────── --}}
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-id-card-line align-middle me-1"></i> Datos de identificación
                </h5>
                <span class="badge {{ $cliente->activo ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                    {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            <div class="card-body">

                {{-- Fila 1: Código y Tipo cliente --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="codigo">Código *</label>
                        <input type="text" name="codigo" id="codigo"
                            class="form-control @error('codigo') is-invalid @enderror"
                            value="{{ old('codigo', $cliente->codigo) }}" placeholder="Ej: CLI001" autocomplete="off">
                        @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="tipo_cliente">Tipo de cliente *</label>
                        <select name="tipo_cliente" id="tipo_cliente" class="form-select">
                            <option value="Cliente" {{ old('tipo_cliente', $cliente->tipo_cliente) === 'Cliente' ? 'selected' : '' }}>Cliente</option>
                        </select>
                    </div>
                </div>

                {{-- Fila 2: Documento + DV + Tipo documento --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="numero_documento">Nro. NIT / CC / Otro *</label>
                        <input type="text" name="numero_documento" id="numero_documento"
                            class="form-control @error('numero_documento') is-invalid @enderror"
                            value="{{ old('numero_documento', $cliente->numero_documento) }}" placeholder="Sin puntos ni espacios" autocomplete="off">
                        @error('numero_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold" for="digito_verificacion">DV</label>
                        <input type="text" name="digito_verificacion" id="digito_verificacion"
                            class="form-control" value="{{ old('digito_verificacion', $cliente->digito_verificacion) }}"
                            placeholder="Ej: 1" maxlength="1" autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="tipo_documento">Tipo de documento *</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-select">
                            <option value="CC"   {{ old('tipo_documento', $cliente->tipo_documento) === 'CC'   ? 'selected' : '' }}>Cédula de ciudadanía</option>
                            <option value="TI"   {{ old('tipo_documento', $cliente->tipo_documento) === 'TI'   ? 'selected' : '' }}>Tarjeta de identidad</option>
                            <option value="CE"   {{ old('tipo_documento', $cliente->tipo_documento) === 'CE'   ? 'selected' : '' }}>Cédula de extranjería</option>
                            <option value="NIT"  {{ old('tipo_documento', $cliente->tipo_documento) === 'NIT'  ? 'selected' : '' }}>NIT</option>
                            <option value="Otro" {{ old('tipo_documento', $cliente->tipo_documento) === 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                </div>

                {{-- Bloque jurídico --}}
                <div id="bloque-juridico" class="d-none">
                    <hr class="my-3">
                    <p class="fw-semibold text-muted mb-3">
                        <i class="ri-building-line align-middle me-1"></i> Datos de la empresa
                    </p>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="razon_social">Razón social / Nombre establecimiento</label>
                            <input type="text" name="razon_social" id="razon_social" class="form-control"
                                value="{{ old('razon_social', $cliente->razon_social) }}" placeholder="Razón social de la empresa">
                        </div>
                    </div>

                    <p class="fw-semibold text-muted mb-3">
                        <i class="ri-user-line align-middle me-1"></i> Representante legal
                    </p>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label" for="rep_legal_primer_nombre">Primer nombre</label>
                            <input type="text" name="rep_legal_primer_nombre" id="rep_legal_primer_nombre"
                                class="form-control" value="{{ old('rep_legal_primer_nombre', $cliente->rep_legal_primer_nombre) }}" placeholder="Primer nombre">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="rep_legal_segundo_nombre">Segundo nombre</label>
                            <input type="text" name="rep_legal_segundo_nombre" id="rep_legal_segundo_nombre"
                                class="form-control" value="{{ old('rep_legal_segundo_nombre', $cliente->rep_legal_segundo_nombre) }}" placeholder="Segundo nombre">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="rep_legal_primer_apellido">Primer apellido</label>
                            <input type="text" name="rep_legal_primer_apellido" id="rep_legal_primer_apellido"
                                class="form-control" value="{{ old('rep_legal_primer_apellido', $cliente->rep_legal_primer_apellido) }}" placeholder="Primer apellido">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="rep_legal_segundo_apellido">Segundo apellido</label>
                            <input type="text" name="rep_legal_segundo_apellido" id="rep_legal_segundo_apellido"
                                class="form-control" value="{{ old('rep_legal_segundo_apellido', $cliente->rep_legal_segundo_apellido) }}" placeholder="Segundo apellido">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="rep_legal_documento">Nro. documento rep. legal</label>
                            <input type="text" name="rep_legal_documento" id="rep_legal_documento"
                                class="form-control" value="{{ old('rep_legal_documento', $cliente->rep_legal_documento) }}" placeholder="Número de documento">
                        </div>
                    </div>
                </div>

                {{-- Bloque natural --}}
                <div id="bloque-natural">
                    <hr class="my-3">
                    <p class="fw-semibold text-muted mb-3">
                        <i class="ri-user-line align-middle me-1"></i> Datos personales
                    </p>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" for="primer_nombre">Primer nombre *</label>
                            <input type="text" name="primer_nombre" id="primer_nombre"
                                class="form-control @error('primer_nombre') is-invalid @enderror"
                                value="{{ old('primer_nombre', $cliente->primer_nombre) }}" placeholder="Primer nombre">
                            @error('primer_nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="segundo_nombre">Segundo nombre</label>
                            <input type="text" name="segundo_nombre" id="segundo_nombre" class="form-control"
                                value="{{ old('segundo_nombre', $cliente->segundo_nombre) }}" placeholder="Segundo nombre">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" for="primer_apellido">Primer apellido *</label>
                            <input type="text" name="primer_apellido" id="primer_apellido"
                                class="form-control @error('primer_apellido') is-invalid @enderror"
                                value="{{ old('primer_apellido', $cliente->primer_apellido) }}" placeholder="Primer apellido">
                            @error('primer_apellido')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="segundo_apellido">Segundo apellido</label>
                            <input type="text" name="segundo_apellido" id="segundo_apellido" class="form-control"
                                value="{{ old('segundo_apellido', $cliente->segundo_apellido) }}" placeholder="Segundo apellido">
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div class="row mb-0">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="email">Correo electrónico *</label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $cliente->email) }}" placeholder="correo@empresa.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Card: Dirección ──────────────────────────────────────── --}}
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-map-pin-line align-middle me-1"></i> Dirección
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold" for="direccion">Dirección *</label>
                        <input type="text" name="direccion" id="direccion"
                            class="form-control @error('direccion') is-invalid @enderror"
                            value="{{ old('direccion', $cliente->direccion) }}" placeholder="Dirección completa">
                        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="ciudad_id">Ciudad</label>
                        <select name="ciudad_id" id="ciudad_id" class="form-select">
                            <option value="">Sin ciudad</option>
                            @foreach ($ciudades as $ciudad)
                                <option value="{{ $ciudad->id }}"
                                    {{ old('ciudad_id', $cliente->ciudad_id) == $ciudad->id ? 'selected' : '' }}>
                                    {{ $ciudad->nombre }}@if ($ciudad->departamento) — {{ $ciudad->departamento }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-md-3">
                        <label class="form-label" for="telefono">Teléfono fijo</label>
                        <input type="text" name="telefono" id="telefono" class="form-control"
                            value="{{ old('telefono', $cliente->telefono) }}" placeholder="Sin espacios ni comas">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="celular">Celular</label>
                        <input type="text" name="celular" id="celular" class="form-control"
                            value="{{ old('celular', $cliente->celular) }}" placeholder="Sin espacios ni comas">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Acciones ──────────────────────────────────────────────── --}}
        <div class="card">
            <div class="card-body d-flex justify-content-between">
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line align-middle me-1"></i> Salir
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="ri-save-line align-middle me-1"></i> Guardar cambios
                </button>
            </div>
        </div>

    </form>

@endsection

@section('scripts')
    @vite(['resources/js/clientes_form.js'])
@endsection