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
    .small { font-size: 11px; }
  </style>
</head>
<body>
  <div class="header">
    <div>
      <img class="logo" src="{{ public_path('vendor/lidervyg/logo.png') }}" alt="Logo">
    </div>
    <div style="text-align:right">
      <div class="title">COTIZACIÓN</div>
      <div><strong>Nº:</strong> {{ $cotizacion->numero }}</div>
      <div><strong>Fecha:</strong> {{ $cotizacion->fecha_emision->format('d/m/Y') }}</div>
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
        <strong>Cliente:</strong> {{ $cotizacion->cliente->nombre_cliente }}<br>
        <strong>RUC:</strong> {{ $cotizacion->cliente->ruc }}<br>
        <strong>Dirección:</strong> {{ $cotizacion->cliente->direccion }}<br>
        <strong>Administrador:</strong> {{ $cotizacion->cliente->nombre_administrador }}<br>
        <strong>Proyecto/Edificio:</strong> {{ $cotizacion->cliente->unidad_inmobiliaria }}
      </td>
    </tr>
  </table>

  <div class="small" style="margin-top:6px">
    <strong>Moneda:</strong> {{ $cotizacion->moneda }} &nbsp;&nbsp;|
    <strong>IGV:</strong> {{ $cotizacion->afecto_igv ? 'Operación Afecta a IGV' : 'Operación No Afecta a IGV' }}
  </div>

  <table class="table">
    <thead>
      <tr>
        <th style="width:70px">Código</th>
        <th>Descripción</th>
        <th style="width:60px" class="text-right">Cant.</th>
        <th style="width:90px" class="text-right">P. Unit.</th>
        <th style="width:90px" class="text-right">Total</th>
        <th style="width:160px">Observaciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($cotizacion->detalles as $d)
      <tr>
        <td>{{ $d->codigo }}</td>
        <td>{{ $d->descripcion }}</td>
        <td class="text-right">{{ $d->cantidad }}</td>
        <td class="text-right">{{ number_format($d->precio_unitario,2) }}</td>
        <td class="text-right">{{ number_format($d->total_linea,2) }}</td>
        <td class="obs">{{ $d->observaciones }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <table style="width:100%; margin-top:10px">
    <tr>
      <td style="width:60%">
        <strong>Observaciones generales:</strong><br>
        {{ $cotizacion->observaciones }}
      </td>
      <td style="width:40%">
        <table class="table">
          <tr><th>Subtotal</th><td class="text-right">{{ number_format($cotizacion->subtotal,2) }}</td></tr>
          <tr><th>IGV</th><td class="text-right">{{ number_format($cotizacion->igv,2) }}</td></tr>
          <tr><th>TOTAL</th><td class="text-right"><strong>{{ number_format($cotizacion->total,2) }}</strong></td></tr>
        </table>
      </td>
    </tr>
  </table>

  <div class="small" style="margin-top:10px">
    <em>Precios ingresados son finales. Si aplica IGV, el sistema lo desglosa automáticamente.</em>
  </div>
</body>
</html>
