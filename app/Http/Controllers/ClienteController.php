<?php

namespace App\Http\Controllers;

use App\Models\ClienteModel;
use App\Models\CiudadModel;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // ─── Listado con búsqueda ────────────────────────────────────
    public function index(Request $request)
    {
        $busqueda  = trim($request->input('busqueda', ''));
        $inactivos = $request->boolean('inactivos');
        $clientes  = collect();
    
        if ($busqueda !== '') {
    
            // Detectar si es código exacto → redirigir a edición
            $exacto = ClienteModel::where('codigo', $busqueda)
                                   ->where('activo', true)
                                   ->first();
    
            if ($exacto) {
                return redirect()->route('clientes.edit', $exacto->id);
            }
    
    
            // Búsqueda progresiva — usando scope del modelo
            $clientes = ClienteModel::where(function ($q) use ($busqueda) {
                                $q->where('codigo',            'like', "%{$busqueda}%")
                                  ->orWhere('razon_social',    'like', "%{$busqueda}%")
                                  ->orWhere('primer_nombre',   'like', "%{$busqueda}%")
                                  ->orWhere('primer_apellido', 'like', "%{$busqueda}%")
                                  ->orWhere('numero_documento','like', "%{$busqueda}%");
                            })
                            ->when(!$inactivos, fn($q) => $q->where('activo', true))
                            ->orderBy('primer_apellido')
                            ->orderBy('razon_social')
                            ->paginate(20)
                            ->withQueryString();
                    }
    
        return view('clientes.index_view', compact(
            'clientes', 
            'busqueda', 
            'inactivos',
        ));
    }

    // ─── Formulario nuevo cliente ────────────────────────────────
    public function create()
    {
        $ciudades = CiudadModel::where('activo', true)
                          ->orderBy('nombre')
                          ->get();

        $ciudadDefault = CiudadModel::where('nombre', 'Pereira')->first();

        return view('clientes.create_view', compact('ciudades', 'ciudadDefault'));
    }

    // ─── Guardar nuevo cliente ───────────────────────────────────
    public function store(Request $request)
    {
        $esNit = $request->filled('digito_verificacion');

        $rules = [
            'codigo'           => 'required|alpha_num|unique:tenant.clientes,codigo',
            'tipo_documento'   => 'required',
            'numero_documento' => 'required|numeric',
            'email'            => 'required|email',
            'direccion'        => 'required',
            'ciudad_id'        => 'nullable|exists:tenant.ciudades,id',
        ];

        // Validaciones según tipo de persona
        if ($esNit) {
            $rules['digito_verificacion'] = 'required|numeric|max:9';
        } else {
            $rules['primer_apellido'] = 'required';
            $rules['primer_nombre']   = 'required';
        }

        $messages = [
            'codigo.required'           => 'El código es obligatorio.',
            'codigo.alpha_num'          => 'El código no puede tener espacios ni caracteres especiales.',
            'codigo.unique'             => 'Este código ya existe. Si desea editar ese cliente búsquelo por su código.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.numeric'  => 'El número de documento solo puede contener números.',
            'email.required'            => 'El correo electrónico es obligatorio.',
            'email.email'               => 'El formato del correo no es válido.',
            'direccion.required'        => 'La dirección es obligatoria.',
            'primer_apellido.required'  => 'El primer apellido es obligatorio para persona natural.',
            'primer_nombre.required'    => 'El primer nombre es obligatorio para persona natural.',
        ];

        $validated = $request->validate($rules, $messages);

        // Tipo de documento automático según dígito de verificación
        $tipoDoc = $esNit ? 'NIT' : ($request->tipo_documento ?? 'CC');

        ClienteModel::create([
            'codigo'                   => strtoupper($request->codigo),
            'tipo_cliente'             => $request->tipo_cliente ?? 'Cliente',
            'tipo_documento'           => $request->tipo_documento,
            'numero_documento'         => $request->numero_documento,
            'digito_verificacion'      => $request->digito_verificacion ?: null,
            'razon_social'             => $esNit ? $request->razon_social              : null,
            'rep_legal_primer_nombre'  => $esNit ? $request->rep_legal_primer_nombre   : null,
            'rep_legal_segundo_nombre' => $esNit ? $request->rep_legal_segundo_nombre  : null,
            'rep_legal_primer_apellido'=> $esNit ? $request->rep_legal_primer_apellido : null,
            'rep_legal_segundo_apellido'=> $esNit ? $request->rep_legal_segundo_apellido: null,
            'rep_legal_documento'      => $esNit ? $request->rep_legal_documento        : null,
            'primer_apellido'          => !$esNit ? $request->primer_apellido  : null,
            'segundo_apellido'         => !$esNit ? $request->segundo_apellido : null,
            'primer_nombre'            => !$esNit ? $request->primer_nombre    : null,
            'segundo_nombre'           => !$esNit ? $request->segundo_nombre   : null,
            'email'                    => $request->email,
            'telefono'                 => $request->telefono,
            'celular'                  => $request->celular,
            'direccion'                => $request->direccion,
            'ciudad_id'                => $request->ciudad_id ?: null,
            'activo'                   => true,
            'cod_lista_precios'        => 1,
            'cod_vendedor'             => '0',
            'id_forma_pago'            => 0,
            'descuento'                => 0,
            'observaciones'            => null,
        ]);

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente creado exitosamente.');
    }

    // ─── Formulario editar cliente ───────────────────────────────
    public function edit($id)
    {
        $cliente = ClienteModel::where('id', $id)
                          ->where('activo', true)
                          ->firstOrFail();

        $ciudades      = CiudadModel::where('activo', true)->orderBy('nombre')->get();
        $ciudadDefault = CiudadModel::where('nombre', 'Pereira')->first();

        return view('clientes.edit_view', compact('cliente', 'ciudades', 'ciudadDefault'));
    }

    // ─── Actualizar cliente ──────────────────────────────────────
    public function update(Request $request, $id)
    {
        $cliente = ClienteModel::where('id', $id)
                          ->where('activo', true)
                          ->firstOrFail();

        $esNit = $request->filled('digito_verificacion');

        $rules = [
            'codigo'           => "required|alpha_num|unique:tenant.clientes,codigo,{$cliente->id}",
            'tipo_documento'   => 'required',
            'numero_documento' => 'required|numeric',
            'email'            => 'required|email',
            'direccion'        => 'required',
            'ciudad_id'        => 'nullable|exists:tenant.ciudades,id',
        ];

        if ($esNit) {
            $rules['digito_verificacion'] = 'required|numeric|max:9';
        } else {
            $rules['primer_apellido'] = 'required';
            $rules['primer_nombre']   = 'required';
        }

        $messages = [
            'codigo.required'           => 'El código es obligatorio.',
            'codigo.alpha_num'          => 'El código no puede tener espacios ni caracteres especiales.',
            'codigo.unique'             => 'Este código ya existe en otro cliente.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.numeric'  => 'El número de documento solo puede contener números.',
            'email.required'            => 'El correo electrónico es obligatorio.',
            'email.email'               => 'El formato del correo no es válido.',
            'direccion.required'        => 'La dirección es obligatoria.',
            'primer_apellido.required'  => 'El primer apellido es obligatorio para persona natural.',
            'primer_nombre.required'    => 'El primer nombre es obligatorio para persona natural.',
        ];

        $request->validate($rules, $messages);

        $tipoDoc = $esNit ? 'NIT' : ($request->tipo_documento ?? 'CC');

        $cliente->update([
            'codigo'                    => strtoupper($request->codigo),
            'tipo_cliente'              => $request->tipo_cliente ?? 'Cliente',
            'tipo_documento'            => $tipoDoc,
            'numero_documento'          => $request->numero_documento,
            'digito_verificacion'       => $request->digito_verificacion ?: null,
            'razon_social'              => $esNit ? $request->razon_social               : null,
            'rep_legal_primer_nombre'   => $esNit ? $request->rep_legal_primer_nombre    : null,
            'rep_legal_segundo_nombre'  => $esNit ? $request->rep_legal_segundo_nombre   : null,
            'rep_legal_primer_apellido' => $esNit ? $request->rep_legal_primer_apellido  : null,
            'rep_legal_segundo_apellido'=> $esNit ? $request->rep_legal_segundo_apellido : null,
            'rep_legal_documento'       => $esNit ? $request->rep_legal_documento        : null,
            'primer_apellido'           => !$esNit ? $request->primer_apellido  : null,
            'segundo_apellido'          => !$esNit ? $request->segundo_apellido : null,
            'primer_nombre'             => !$esNit ? $request->primer_nombre    : null,
            'segundo_nombre'            => !$esNit ? $request->segundo_nombre   : null,
            'email'                     => $request->email,
            'telefono'                  => $request->telefono,
            'celular'                   => $request->celular,
            'direccion'                 => $request->direccion,
            'ciudad_id'                 => $request->ciudad_id ?: null,
            'cod_lista_precios'        => 1,
            'cod_vendedor'             => '0',
            'id_forma_pago'            => 0,
            'descuento'                => 0,
            'observaciones'            => null,
        ]);

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente actualizado exitosamente.');
    }

    // ─── Eliminación lógica ──────────────────────────────────────
    public function destroy($id)
    {
        $cliente = ClienteModel::where('id', $id)
                          ->where('activo', true)
                          ->firstOrFail();
    
        $cliente->update(['activo' => false]);
    
        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente desactivado correctamente.');
    }

    // ─── Activacion lógica ──────────────────────────────────────
    public function reactivar($id)
    {
        $cliente = ClienteModel::where('id', $id)
                               ->where('activo', false)
                               ->firstOrFail();

        $cliente->update(['activo' => true]);

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente reactivado correctamente.');
    }

        // ─── Modal crear cliente ─────────────────────────────────────
    public function createModal()
    {
        $ciudades      = CiudadModel::activas()->get();
        $ciudadDefault = CiudadModel::where('nombre', 'Pereira')->first();

        return view('partials.modals.modal_crear_cliente',
                    compact('ciudades', 'ciudadDefault'));
    }

    // ─── Guardar cliente desde modal ─────────────────────────────
    public function storeModal(Request $request)
    {
        // Misma lógica que store() pero devuelve JSON
        $esNit = $request->filled('digito_verificacion');

        $rules = [
            'codigo'           => 'required|alpha_num|unique:tenant.clientes,codigo',
            'tipo_documento'   => 'required',
            'numero_documento' => 'required|numeric',
            'email'            => 'nullable|email',
            'direccion'        => 'required',
        ];

        if ($esNit) {
            $rules['razon_social'] = 'required';
        } else {
            $rules['primer_apellido'] = 'required';
            $rules['primer_nombre']   = 'required';
        }

        $request->validate($rules);

        $cliente = ClienteModel::create([
            'codigo'              => strtoupper($request->codigo),
            'tipo_cliente'        => 'Cliente',
            'tipo_documento'      => $request->tipo_documento,
            'numero_documento'    => $request->numero_documento,
            'digito_verificacion' => $request->digito_verificacion ?: null,
            'razon_social'        => $esNit ? $request->razon_social : null,
            'primer_apellido'     => !$esNit ? $request->primer_apellido : null,
            'segundo_apellido'    => !$esNit ? $request->segundo_apellido : null,
            'primer_nombre'       => !$esNit ? $request->primer_nombre : null,
            'segundo_nombre'      => !$esNit ? $request->segundo_nombre : null,
            'email'               => $request->email,
            'telefono'            => $request->telefono,
            'celular'             => $request->celular,
            'direccion'           => $request->direccion,
            'ciudad_id'           => $request->ciudad_id ?: null,
            'activo'              => true,
            'cod_lista_precios'   => 1,
            'cod_vendedor'        => '0',
            'id_forma_pago'       => 0,
            'descuento'           => 0,
        ]);

        return response()->json([
            'success' => true,
            'codigo'  => $cliente->codigo,
            'nombre'  => $cliente->nombre_completo,
        ]);
    }

    public function buscarModal()
    {
        return view('partials.modals.modal_buscar_clientes');
    }
}