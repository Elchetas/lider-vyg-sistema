@php($c = $cotizacion)
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">Fecha emisión</label>
    <input type="date" name="fecha_emision" class="form-control" required value="{{ old('fecha_emision', optional($c?->fecha_emision)->format('Y-m-d') ?? date('Y-m-d')) }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Cliente</label>
    <select name="cliente_id" class="form-select" required>
      <option value="">-- Seleccione --</option>
      @foreach($clientes as $cl)
        <option value="{{ $cl->id }}" {{ (string)old('cliente_id', $c->cliente_id ?? '') === (string)$cl->id ? 'selected' : '' }}>{{ $cl->nombre_cliente }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Moneda</label>
    @php($mon = old('moneda', $c->moneda ?? 'PEN'))
    <select name="moneda" class="form-select">
      <option value="PEN" {{ $mon==='PEN'?'selected':'' }}>PEN</option>
      <option value="USD" {{ $mon==='USD'?'selected':'' }}>USD</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Afecto a IGV</label>
    @php($af = old('afecto_igv', $c?->afecto_igv ?? true))
    <select name="afecto_igv" class="form-select">
      <option value="1" {{ (string)$af==='1' || $af===true ? 'selected':'' }}>Sí</option>
      <option value="0" {{ (string)$af==='0' || $af===false ? 'selected':'' }}>No</option>
    </select>
    <div class="form-text">Los precios ingresados son finales. Si está afecto, el sistema separa IGV 18%.</div>
  </div>
  <div class="col-md-9">
    <label class="form-label">Observaciones generales</label>
    <input name="observaciones" class="form-control" value="{{ old('observaciones', $c->observaciones ?? '') }}">
  </div>
</div>

<hr>
<h5>Ítems</h5>
<div class="table-responsive">
<table class="table table-sm align-middle" id="items-table">
  <thead>
    <tr>
      <th style="width:190px">Producto</th>
      <th style="width:110px">Código</th>
      <th>Descripción</th>
      <th style="width:90px">Cant.</th>
      <th style="width:140px">Precio Unit.</th>
      <th style="width:140px">Total</th>
      <th style="width:220px">Observaciones</th>
      <th style="width:50px"></th>
    </tr>
  </thead>
  <tbody>
    @php($oldItems = old('items'))
    @if($oldItems)
      @foreach($oldItems as $i => $it)
        @include('cotizaciones.row', ['i'=>$i, 'item'=>$it])
      @endforeach
    @elseif($c)
      @foreach($c->detalles as $i => $d)
        @include('cotizaciones.row', ['i'=>$i, 'item'=>[
          'producto_id'=>$d->producto_id,
          'codigo'=>$d->codigo,
          'descripcion'=>$d->descripcion,
          'cantidad'=>$d->cantidad,
          'precio_unitario'=>$d->precio_unitario,
          'observaciones'=>$d->observaciones,
        ]])
      @endforeach
    @else
      @include('cotizaciones.row', ['i'=>0, 'item'=>['producto_id'=>'','codigo'=>'','descripcion'=>'','cantidad'=>1,'precio_unitario'=>0,'observaciones'=>'']])
    @endif
  </tbody>
</table>
</div>
<button type="button" class="btn btn-outline-primary btn-sm" id="add-row">+ Agregar ítem</button>

@push('scripts')
<script>
  const productos = @json($productos->map(fn($p)=>[
    'id'=>$p->id,'codigo'=>$p->codigo,'nombre'=>$p->nombre_producto,'descripcion'=>$p->descripcion,'precio'=>(float)$p->precio
  ]));

  function recalcRow(tr){
    const qty = parseFloat(tr.querySelector('[name$="[cantidad]"]').value||0);
    const price = parseFloat(tr.querySelector('[name$="[precio_unitario]"]').value||0);
    tr.querySelector('.total-cell').innerText = (qty*price).toFixed(2);
  }

  document.querySelectorAll('#items-table tbody tr').forEach(tr => {
    tr.addEventListener('input', ()=>recalcRow(tr));
    tr.querySelector('.btn-del')?.addEventListener('click', ()=>{ tr.remove(); });
    tr.querySelector('.prod-select')?.addEventListener('change', (e)=>{
      const id = e.target.value;
      const p = productos.find(x=>String(x.id)===String(id));
      if(p){
        tr.querySelector('.codigo-input').value = p.codigo || '';
        if(!tr.querySelector('.desc-input').value) tr.querySelector('.desc-input').value = p.nombre;
        if(!parseFloat(tr.querySelector('.price-input').value)) tr.querySelector('.price-input').value = p.precio;
        recalcRow(tr);
      }
    });
    recalcRow(tr);
  });

  document.getElementById('add-row').addEventListener('click', ()=>{
    const tbody = document.querySelector('#items-table tbody');
    const idx = tbody.querySelectorAll('tr').length;
    const tpl = document.getElementById('row-template').innerHTML.replaceAll('__i__', idx);
    const temp = document.createElement('tbody');
    temp.innerHTML = tpl;
    const tr = temp.querySelector('tr');
    tbody.appendChild(tr);

    tr.addEventListener('input', ()=>recalcRow(tr));
    tr.querySelector('.btn-del').addEventListener('click', ()=>{ tr.remove(); });
    tr.querySelector('.prod-select').addEventListener('change', (e)=>{
      const id = e.target.value;
      const p = productos.find(x=>String(x.id)===String(id));
      if(p){
        tr.querySelector('.codigo-input').value = p.codigo || '';
        if(!tr.querySelector('.desc-input').value) tr.querySelector('.desc-input').value = p.nombre;
        if(!parseFloat(tr.querySelector('.price-input').value)) tr.querySelector('.price-input').value = p.precio;
        recalcRow(tr);
      }
    });
    recalcRow(tr);
  });
</script>

<script type="text/template" id="row-template">
  @include('cotizaciones.row', ['i'=>'__i__', 'item'=>['producto_id'=>'','codigo'=>'','descripcion'=>'','cantidad'=>1,'precio_unitario'=>0,'observaciones'=>'']])
</script>
@endpush
