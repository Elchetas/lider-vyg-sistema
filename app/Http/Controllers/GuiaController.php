<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Guia;
use App\Models\EmpresaConfig;
use App\Services\VentasMensualesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facades\Pdf;

class GuiaController extends Controller
{
    public function index()
    {
        $guias = Guia::with('cotizacion.cliente')->orderBy('id','desc')->paginate(15);
        return view('guias.index', compact('guias'));
    }

    public function show(Guia $guia)
    {
        $guia->load(['cotizacion.cliente','cotizacion.detalles']);
        return view('guias.show', compact('guia'));
    }

    public function generar(Request $request, Cotizacion $cotizacion, VentasMensualesService $mensual)
    {
        if ($cotizacion->estado !== 'Pendiente') {
            return back()->with('err','Solo se puede generar guía desde una cotización pendiente');
        }
        if ($cotizacion->guia) {
            return back()->with('err','Esta cotización ya tiene guía');
        }

        return DB::transaction(function () use ($cotizacion, $mensual) {
            $guia = Guia::create([
                'cotizacion_id' => $cotizacion->id,
                'numero' => $cotizacion->numero,
                'fecha' => now()->toDateString(),
                'estado' => 'Emitida',
            ]);

            $cotizacion->update(['estado' => 'Aprobada']);

            // Recalcular ventas del mes de la fecha de emisión de la cotización
            $mes = $cotizacion->fecha_emision->format('Y-m');
            $mensual->recomputeForMonth($mes);

            return redirect()->route('guias.show', $guia)->with('ok','Guía generada. La cotización quedó aprobada y bloqueada.');
        });
    }

    public function pdf(Guia $guia)
    {
        $guia->load(['cotizacion.cliente','cotizacion.detalles']);
        $empresa = EmpresaConfig::first();
        $pdf = Pdf::loadView('guias.pdf', [
            'guia' => $guia,
            'empresa' => $empresa,
        ])->setPaper('a4');

        return $pdf->download('GUIA_'.$guia->numero.'.pdf');
    }
}
