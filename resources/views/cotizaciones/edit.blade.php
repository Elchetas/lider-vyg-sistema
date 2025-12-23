@extends('layouts.app')
@section('content')
<h3>Editar Cotización Nº {{ $cotizacion->numero }}</h3>
<form method="POST" action="{{ route('cotizaciones.update',$cotizacion) }}" class="mt-3" id="cot-form">
@csrf @method('PUT')
@include('cotizaciones.form', ['cotizacion'=>$cotizacion])
<button class="btn btn-primary mt-3">Guardar cambios</button>
<a class="btn btn-outline-secondary mt-3" href="{{ route('cotizaciones.show',$cotizacion) }}">Volver</a>
</form>
@endsection
