<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .header { display: flex; justify-content: space-between; align-items: center; }
    .logo { height: 60px; }
    .title { font-size: 18px; font-weight: bold; }
    .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .table th, .table td { border: 1px solid #bbb; padding: 6px; }
    .table th { background: #f0f0f0; }
    .text-right { text-align: right; }
    .obs { color: #d32f2f; font-weight: 600; }
  </style>
</head>
<body>
  <div class="header">
    <div>
      <img class="logo" src="{{ public_path('vendor/lidervyg/logo.png') }}" alt="Logo">
    </div>
    <div style="text-align:right">
      <div class="title">GUÍA</div>
      <div><strong>Nº:</strong> {{ $guia->numero }}</div>
      <div><strong>Fecha:</strong> {{ $guia->fecha->format('d/m/Y') }}</div>
    </div>
  </div>

  <hr>

  <table style="width:100%">
    <tr>
      <td>
        <strong>{{ $empresa->nombre_empresa ?? 'Líder V y G' }}</strong><br>
        Email: {{ $empresa->email ?? '' }}<br>
        Tel: {{ $empresa->telefono ?? '' }}
      </td>
      <td>
        <strong>Cliente:</strong> {{ $guia->cotizacion->cliente->nombre_cliente }}<br>
        <strong>RUC:</strong> {{ $guia->cotizacion->cliente->ruc }}<br>
        <strong>Dirección:</strong> {{ $guia->cotizacion->cliente->direccion }}<br>
        <strong>Administrador:</strong> {{ $guia->cotizacion->cliente->nombre_administrador }}<br>
        <strong>Proyecto/Edificio:</strong> {{ $guia->cotizacion->cliente->unidad_inmobiliaria }}
      </td>
    </tr>
  </table>

  <div style="margin-top:6px"><strong>Cotización asociada:</strong> {{ $guia->cotizacion->numero }}</div>

  <table class="table">
    <thead>
      <tr>
        <th style="width:80px">Código</th>
        <th>Descripción</th>
        <th style="width:70px" class="text-right">Cant.</th>
        <th style="width:200px">Observaciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($guia->cotizacion->detalles as $d)
      <tr>
        <td>{{ $d->codigo }}</td>
        <td>{{ $d->descripcion }}</td>
        <td class="text-right">{{ $d->cantidad }}</td>
        <td class="obs">{{ $d->observaciones }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div style="margin-top:10px">
    <em>Esta guía corresponde a una cotización aprobada. No incluye precios.</em>
  </div>
</body>
</html>
