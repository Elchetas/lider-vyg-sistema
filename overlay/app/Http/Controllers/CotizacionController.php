<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CatalogoProducto;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\EmpresaConfig;
use App\Services\NumberingService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facades\Pdf;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = Cotizacion::with('cliente')->orderBy('id','desc')->paginate(15);
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $clientes = Cliente::where('activo',true)->orderBy('nombre_cliente')->get();
        $productos = CatalogoProducto::where('activo',true)->orderBy('codigo')->get();
        return view('cotizaciones.create', compact('clientes','productos'));
    }

    public function store(Request $request, NumberingService $numbering, PricingService $pricing)
    {
        $data = $request->validate([
            'fecha_emision' => 'required|date',
            'cliente_id' => 'required|exists:clientes,id',
            'moneda' => 'required|in:PEN,USD',
            'afecto_igv' => 'required|boolean',
            'observaciones' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'nullable|exists:catalogo_productos,id',
            'items.*.codigo' => 'nullable|string|max:255',
            'items.*.descripcion' => 'required|string',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0',
            'items.*.observaciones' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($data, $numbering, $pricing, $request) {
            $numero = $numbering->nextCotizacionNumero(now());

            $cot = Cotizacion::create([
                'numero' => $numero,
                'fecha_emision' => $data['fecha_emision'],
                'cliente_id' => $data['cliente_id'],
                'moneda' => $data['moneda'],
                'afecto_igv' => (bool)$data['afecto_igv'],
                'estado' => 'Pendiente',
                'observaciones' => $data['observaciones'] ?? null,
                'creado_por' => $request->user()->id,
            ]);

            $lineTotals = [];
            foreach ($data['items'] as $item) {
                $totalLinea = round($item['cantidad'] * (float)$item['precio_unitario'], 2);
                $lineTotals[] = $totalLinea;

                CotizacionDetalle::create([
                    'cotizacion_id' => $cot->id,
                    'producto_id' => $item['producto_id'] ?? null,
                    'codigo' => $item['codigo'] ?? null,
                    'descripcion' => $item['descripcion'],
                    'cantidad' => (int)$item['cantidad'],
                    'precio_unitario' => (float)$item['precio_unitario'],
                    'total_linea' => $totalLinea,
                    'observaciones' => $item['observaciones'] ?? null,
                ]);
            }

            $igvRate = (float)(EmpresaConfig::first()->igv_rate ?? 0.18);
            [$subtotal, $igv, $total] = $pricing->totals($lineTotals, $cot->afecto_igv, $igvRate);
            $cot->update(['subtotal' => $subtotal, 'igv' => $igv, 'total' => $total]);

            return redirect()->route('cotizaciones.show', $cot)->with('ok', 'Cotización creada');
        });
    }

    public function show(Cotizacion $cotizacion)
    {
        $cotizacion->load(['cliente','detalles']);
        return view('cotizaciones.show', compact('cotizacion'));
    }

    public function edit(Cotizacion $cotizacion)
    {
        if ($cotizacion->estado !== 'Pendiente') {
            return redirect()->route('cotizaciones.show', $cotizacion)->with('err','No se puede editar: ya fue aprobada/anulada');
        }
        $cotizacion->load('detalles');
        $clientes = Cliente::where('activo',true)->orderBy('nombre_cliente')->get();
        $productos = CatalogoProducto::where('activo',true)->orderBy('codigo')->get();
        return view('cotizaciones.edit', compact('cotizacion','clientes','productos'));
    }

    public function update(Request $request, Cotizacion $cotizacion, PricingService $pricing)
    {
        if ($cotizacion->estado !== 'Pendiente') {
            return redirect()->route('cotizaciones.show', $cotizacion)->with('err','No se puede editar: ya fue aprobada/anulada');
        }

        $data = $request->validate([
            'fecha_emision' => 'required|date',
            'cliente_id' => 'required|exists:clientes,id',
            'moneda' => 'required|in:PEN,USD',
            'afecto_igv' => 'required|boolean',
            'observaciones' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'nullable|exists:catalogo_productos,id',
            'items.*.codigo' => 'nullable|string|max:255',
            'items.*.descripcion' => 'required|string',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0',
            'items.*.observaciones' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($cotizacion, $data, $pricing) {
            $cotizacion->update([
                'fecha_emision' => $data['fecha_emision'],
                'cliente_id' => $data['cliente_id'],
                'moneda' => $data['moneda'],
                'afecto_igv' => (bool)$data['afecto_igv'],
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            $cotizacion->detalles()->delete();
            $lineTotals = [];
            foreach ($data['items'] as $item) {
                $totalLinea = round($item['cantidad'] * (float)$item['precio_unitario'], 2);
                $lineTotals[] = $totalLinea;
                CotizacionDetalle::create([
                    'cotizacion_id' => $cotizacion->id,
                    'producto_id' => $item['producto_id'] ?? null,
                    'codigo' => $item['codigo'] ?? null,
                    'descripcion' => $item['descripcion'],
                    'cantidad' => (int)$item['cantidad'],
                    'precio_unitario' => (float)$item['precio_unitario'],
                    'total_linea' => $totalLinea,
                    'observaciones' => $item['observaciones'] ?? null,
                ]);
            }

            $igvRate = (float)(EmpresaConfig::first()->igv_rate ?? 0.18);
            [$subtotal, $igv, $total] = $pricing->totals($lineTotals, $cotizacion->afecto_igv, $igvRate);
            $cotizacion->update(['subtotal' => $subtotal, 'igv' => $igv, 'total' => $total]);

            return redirect()->route('cotizaciones.show', $cotizacion)->with('ok','Cotización actualizada');
        });
    }

    public function destroy(Cotizacion $cotizacion)
    {
        if ($cotizacion->estado === 'Aprobada') {
            return back()->with('err','No se puede eliminar una cotización aprobada');
        }
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')->with('ok','Cotización eliminada');
    }

    public function recalcular(Cotizacion $cotizacion, PricingService $pricing)
    {
        $cotizacion->load('detalles');
        $lineTotals = $cotizacion->detalles->pluck('total_linea')->map(fn($v)=>(float)$v)->toArray();
        $igvRate = (float)(EmpresaConfig::first()->igv_rate ?? 0.18);
        [$subtotal, $igv, $total] = $pricing->totals($lineTotals, $cotizacion->afecto_igv, $igvRate);
        $cotizacion->update(['subtotal' => $subtotal, 'igv' => $igv, 'total' => $total]);
        return back()->with('ok','Totales recalculados');
    }

    public function pdf(Cotizacion $cotizacion)
    {
        $cotizacion->load(['cliente','detalles']);
        $empresa = EmpresaConfig::first();
        $pdf = Pdf::loadView('cotizaciones.pdf', [
            'cotizacion' => $cotizacion,
            'empresa' => $empresa,
        ])->setPaper('a4');

        return $pdf->download('COT_'.$cotizacion->numero.'.pdf');
    }
}
