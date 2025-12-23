<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Líder V y G - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .obs-roja { color: #d32f2f; font-weight: 600; }
        .logo-top { height: 44px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('dashboard') }}">
        <img src="{{ asset('vendor/lidervyg/logo.png') }}" class="logo-top me-2" alt="Logo">
        Líder V y G
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="{{ route('clientes.index') }}">Clientes</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('productos.index') }}">Catálogo</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('cotizaciones.index') }}">Cotizaciones</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('guias.index') }}">Guías</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('reportes.sunat') }}">Reporte SUNAT</a></li>
      </ul>
      <div class="d-flex">
        <span class="navbar-text text-white me-3">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-outline-light btn-sm" type="submit">Salir</button>
        </form>
      </div>
    </div>
  </div>
</nav>

<div class="container py-4">
    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif
    @if(session('err'))
        <div class="alert alert-danger">{{ session('err') }}</div>
    @endif
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
