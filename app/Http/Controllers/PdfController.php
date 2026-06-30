<?php

namespace App\Http\Controllers;

use App\Models\ClienteModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/**
 * Controlador centralizado de generación de PDFs.
 * Un método por tipo de documento — patrón ya usado en click-pyme (Beta 4).
 */
class PdfController extends Controller
{
    /**
     * Genera el listado de clientes activos en PDF, con el mismo filtro
     * de búsqueda que el listado web (ClienteController::index).
     */
    public function clientes(Request $request)
    {
        $busqueda = trim($request->input('busqueda', ''));

        $query = ClienteModel::where('activo', true)
                        ->with('ciudad')
                        ->orderBy('primer_apellido')
                        ->orderBy('razon_social');

        if ($busqueda !== '') {
            $query->where(function ($q) use ($busqueda) {
                $q->where('primer_nombre',    'LIKE', "%{$busqueda}%")
                  ->orWhere('primer_apellido', 'LIKE', "%{$busqueda}%")
                  ->orWhere('razon_social',    'LIKE', "%{$busqueda}%")
                  ->orWhere('numero_documento','LIKE', "%{$busqueda}%")
                  ->orWhere('email',           'LIKE', "%{$busqueda}%")
                  ->orWhere('codigo',          'LIKE', "%{$busqueda}%");
            });
        }

        $clientes        = $query->get();
        $totalClientes   = $clientes->count();
        $fechaGeneracion = now()->format('d/m/Y H:i');
        $empresa         = session('tenant_nombre');

        $pdf = Pdf::loadView('pdf.clientespdf', compact(
            'clientes',
            'totalClientes',
            'fechaGeneracion',
            'empresa',
            'busqueda'
        ))
        ->setPaper('letter', 'landscape')
        ->setOption('defaultFont', 'sans-serif')
        ->setOption('isRemoteEnabled', true);

        $nombreArchivo = 'clientes_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($nombreArchivo);
    }
}