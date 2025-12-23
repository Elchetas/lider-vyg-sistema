@extends('layouts.app')
@section('content')
<h3>Nueva Cotización</h3>
<form method="POST" action="{{ route('cotizaciones.store') }}" class="mt-3" id="cot-form">
@csrf
@include('cotizaciones.form', ['cotizacion'=>null])
<button class="btn btn-primary mt-3">Guardar</button>
<a class="btn btn-outline-secondary mt-3" href="{{ route('cotizaciones.index') }}">Volver</a>
</form>
@endsection

@push('scripts')
@include('cotizaciones.form_scripts')
@endpush
