@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Cotizaciones</h3>
  <a class="btn btn-primary" href="{{ route('cotizaciones.create') }}">Nueva</a>
</div>
<table class="table table-sm table-striped">
  <thead><tr>
    <th>Número</th><th>Fecha</th><th>Cliente</th><th>Estado</th><th>Total</th><th></th>
  </tr></thead>
  <tbody>
  @foreach($cotizaciones as $c)
    <tr>
      <td>{{ $c->numero }}</td>
      <td>{{ $c->fecha_emision->format('d/m/Y') }}</td>
      <td>{{ $c->cliente?->nombre_cliente }}</td>
      <td>{{ $c->estado }}</td>
      <td>{{ number_format($c->total,2) }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('cotizaciones.show',$c) }}">Ver</a>
        @if($c->estado==='Pendiente')
          <a class="btn btn-sm btn-outline-primary" href="{{ route('cotizaciones.edit',$c) }}">Editar</a>
        @endif
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $cotizaciones->links() }}
@endsection
