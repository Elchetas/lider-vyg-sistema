@php($p = $producto)
<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Código</label>
    <input name="codigo" class="form-control" required value="{{ old('codigo', $p->codigo ?? '') }}">
  </div>
  <div class="col-md-8">
    <label class="form-label">Nombre Producto</label>
    <input name="nombre_producto" class="form-control" required value="{{ old('nombre_producto', $p->nombre_producto ?? '') }}">
  </div>
  <div class="col-12">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $p->descripcion ?? '') }}</textarea>
  </div>
  <div class="col-md-4">
    <label class="form-label">Precio (final)</label>
    <input name="precio" type="number" step="0.01" class="form-control" required value="{{ old('precio', $p->precio ?? 0) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Precio Proveedor</label>
    <input name="precio_prov" type="number" step="0.01" class="form-control" value="{{ old('precio_prov', $p->precio_prov ?? 0) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Proveedor</label>
    <input name="proveedor" class="form-control" value="{{ old('proveedor', $p->proveedor ?? '') }}">
  </div>
  <div class="col-12">
    <label class="form-label">Observaciones</label>
    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $p->observaciones ?? '') }}</textarea>
  </div>
  <div class="col-md-6">
    <label class="form-label">Imagen</label>
    <input name="imagen" type="file" accept="image/*" class="form-control">
    @if(!empty($p?->imagen_path))
      <div class="mt-2"><img src="/{{ $p->imagen_path }}" style="height:80px"></div>
    @endif
  </div>
  <div class="col-md-6 d-flex align-items-end">
    @php($act = old('activo', $p->activo ?? true))
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="activo" value="1" {{ $act ? 'checked' : '' }}>
      <label class="form-check-label">Activo</label>
    </div>
  </div>
</div>
