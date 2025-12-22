<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\SunatReportController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('clientes', ClienteController::class);
    Route::resource('productos', ProductoController::class);

    Route::get('cotizaciones/{cotizacion}/pdf', [CotizacionController::class, 'pdf'])->name('cotizaciones.pdf');
    Route::post('cotizaciones/{cotizacion}/recalcular', [CotizacionController::class, 'recalcular'])->name('cotizaciones.recalcular');
    Route::resource('cotizaciones', CotizacionController::class);

    // Solo ADMIN: generar guía
    Route::post('cotizaciones/{cotizacion}/generar-guia', [GuiaController::class, 'generar'])
        ->middleware('admin')
        ->name('cotizaciones.generar_guia');

    Route::get('guias/{guia}/pdf', [GuiaController::class, 'pdf'])->name('guias.pdf');
    Route::resource('guias', GuiaController::class)->only(['index','show']);

    Route::get('reportes/sunat', [SunatReportController::class, 'index'])->name('reportes.sunat');
    Route::get('reportes/sunat/export', [SunatReportController::class, 'export'])->name('reportes.sunat.export');
});

require __DIR__.'/auth.php';
