@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center">
  <h3>Guía Nº {{ $guia->numero }}</h3>
  <a class="btn btn-outline-primary" href="{{ route('guias.pdf',$guia) }}">PDF</a>
</div>
<div class="card mt-3"><div class="card-body">
  <div class="row">
    <div class="col-md-4"><strong>Fecha:</strong> {{ $guia->fecha->format('d/m/Y') }}</div>
    <div class="col-md-8"><strong>Cliente:</strong> {{ $guia->cotizacion?->cliente?->nombre_cliente }}</div>
    <div class="col-md-4 mt-2"><strong>Cotización:</strong> {{ $guia->cotizacion?->numero }}</div>
    <div class="col-md-4 mt-2"><strong>Estado:</strong> {{ $guia->estado }}</div>
    <div class="col-md-4 mt-2"><strong>IGV:</strong> {{ $guia->cotizacion?->afecto_igv ? 'Afecto' : 'No afecto' }}</div>
  </div>
</div></div>

<div class="table-responsive mt-3">
<table class="table table-sm table-striped">
  <thead><tr>
    <th>Código</th><th>Descripción</th><th class="text-end">Cantidad</th><th>Observaciones</th>
  </tr></thead>
  <tbody>
    @foreach($guia->cotizacion->detalles as $d)
      <tr>
        <td>{{ $d->codigo }}</td>
        <td>{{ $d->descripcion }}</td>
        <td class="text-end">{{ $d->cantidad }}</td>
        <td class="obs-roja">{{ $d->observaciones }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
</div>

<a class="btn btn-outline-secondary mt-3" href="{{ route('guias.index') }}">Volver</a>
@endsection
