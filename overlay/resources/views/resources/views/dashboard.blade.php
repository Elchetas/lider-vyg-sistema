@extends('layouts.app')

@section('content')
<div class="row g-3">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Bienvenido</h4>
        <p class="card-text">Sistema de clientes, catálogo, cotizaciones, guías y reporte SUNAT.</p>
        <div class="d-flex gap-2">
          <a class="btn btn-primary" href="{{ route('cotizaciones.create') }}">Nueva Cotización</a>
          <a class="btn btn-outline-primary" href="{{ route('productos.index') }}">Ver Catálogo</a>
          <a class="btn btn-outline-secondary" href="{{ route('reportes.sunat') }}">Reporte SUNAT</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
