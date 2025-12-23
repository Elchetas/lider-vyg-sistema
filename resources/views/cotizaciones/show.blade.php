@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center">
  <h3>Cotización Nº {{ $cotizacion->numero }}</h3>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-secondary" href="{{ route('cotizaciones.pdf',$cotizacion) }}">PDF</a>
    @if($cotizacion->estado==='Pendiente')
      <a class="btn btn-outline-primary" href="{{ route('cotizaciones.edit',$cotizacion) }}">Editar</a>
    @endif
    @if($cotizacion->estado==='Pendiente' && auth()->user()->isAdmin())
      <form method="POST" action="{{ route('cotizaciones.generar_guia',$cotizacion) }}" onsubmit="return confirm('¿Aprobar y generar guía? Luego no se podrá editar.');">
        @csrf
        <button class="btn btn-success">Aprobar / Generar Guía</button>
      </form>
    @endif
  </div>
</div>

<div class="card mt-3"><div class="card-body">
  <div class="row">
    <div class="col-md-3"><strong>Fecha:</strong> {{ $cotizacion->fecha_emision->format('d/m/Y') }}</div>
    <div class="col-md-5"><strong>Cliente:</strong> {{ $cotizacion->cliente?->nombre_cliente }}</div>
    <div class="col-md-2"><strong>Moneda:</strong> {{ $cotizacion->moneda }}</div>
    <div class="col-md-2"><strong>Estado:</strong> {{ $cotizacion->estado }}</div>
    <div class="col-md-3 mt-2"><strong>IGV:</strong> {{ $cotizacion->afecto_igv ? 'Afecto' : 'No afecto' }}</div>
    <div class="col-md-9 mt-2"><strong>Obs. generales:</strong> {{ $cotizacion->observaciones }}</div>
  </div>
</div></div>

<div class="table-responsive mt-3">
<table class="table table-sm table-striped">
  <thead><tr>
    <th>Código</th><th>Descripción</th><th class="text-end">Cant.</th><th class="text-end">Precio Unit.</th><th class="text-end">Total</th><th>Observaciones</th>
  </tr></thead>
  <tbody>
    @foreach($cotizacion->detalles as $d)
      <tr>
        <td>{{ $d->codigo }}</td>
        <td>{{ $d->descripcion }}</td>
        <td class="text-end">{{ $d->cantidad }}</td>
        <td class="text-end">{{ number_format($d->precio_unitario,2) }}</td>
        <td class="text-end">{{ number_format($d->total_linea,2) }}</td>
        <td class="obs-roja">{{ $d->observaciones }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
</div>

<div class="row justify-content-end">
  <div class="col-md-4">
    <div class="card"><div class="card-body">
      <div class="d-flex justify-content-between"><span>Subtotal</span><strong>{{ number_format($cotizacion->subtotal,2) }}</strong></div>
      <div class="d-flex justify-content-between"><span>IGV</span><strong>{{ number_format($cotizacion->igv,2) }}</strong></div>
      <hr>
      <div class="d-flex justify-content-between"><span>TOTAL</span><strong>{{ number_format($cotizacion->total,2) }}</strong></div>
    </div></div>
  </div>
</div>

@if($cotizacion->guia)
  <div class="alert alert-info mt-3">
    Esta cotización ya fue aprobada. Guía Nº <strong>{{ $cotizacion->guia->numero }}</strong>.
    <a href="{{ route('guias.show',$cotizacion->guia) }}">Ver guía</a>.
  </div>
@endif

<a class="btn btn-outline-secondary mt-3" href="{{ route('cotizaciones.index') }}">Volver</a>
@endsection
