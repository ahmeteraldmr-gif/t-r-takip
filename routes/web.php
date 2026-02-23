<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\FuelExpenseController;
use App\Http\Controllers\OtherExpenseController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\TruckDocumentController;
use App\Http\Controllers\TireController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['ensure.user'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    Route::middleware(['patron'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('/aylik-ozet', [\App\Http\Controllers\MonthlySummaryController::class, 'index'])
            ->middleware('admin')->name('monthly-summary.index');
        Route::resource('customers', CustomerController::class);
        Route::resource('quotations', QuotationController::class);
        Route::resource('maintenances', MaintenanceController::class)->except(['show']);
        Route::post('/maintenances/{maintenance}/mark-done', [MaintenanceController::class, 'markDone'])->name('maintenances.mark-done');
        Route::resource('trucks', TruckController::class)->except(['show']);
        Route::get('/truck-documents/create', [TruckDocumentController::class, 'create'])->name('truck-documents.create');
        Route::post('/truck-documents', [TruckDocumentController::class, 'store'])->name('truck-documents.store');
        Route::get('/truck-documents/{truckDocument}/download', [TruckDocumentController::class, 'download'])->name('truck-documents.download');
        Route::delete('/truck-documents/{truckDocument}', [TruckDocumentController::class, 'destroy'])->name('truck-documents.destroy');
        Route::get('/tires/create', [TireController::class, 'create'])->name('tires.create');
        Route::post('/tires', [TireController::class, 'store'])->name('tires.store');
        Route::get('/tires/{tire}/edit', [TireController::class, 'edit'])->name('tires.edit');
        Route::put('/tires/{tire}', [TireController::class, 'update'])->name('tires.update');
        Route::delete('/tires/{tire}', [TireController::class, 'destroy'])->name('tires.destroy');
        Route::get('/drivers', fn () => redirect()->route('trucks.index'))->name('drivers.index');
        Route::get('/drivers/create', [\App\Http\Controllers\DriverController::class, 'create'])->name('drivers.create');
        Route::post('/drivers', [\App\Http\Controllers\DriverController::class, 'store'])->name('drivers.store');
        Route::get('/drivers/{driver}', [\App\Http\Controllers\DriverController::class, 'show'])->name('drivers.show');
        Route::get('/drivers/{driver}/edit', [\App\Http\Controllers\DriverController::class, 'edit'])->name('drivers.edit');
        Route::put('/drivers/{driver}', [\App\Http\Controllers\DriverController::class, 'update'])->name('drivers.update');
        Route::delete('/drivers/{driver}', [\App\Http\Controllers\DriverController::class, 'destroy'])->name('drivers.destroy');
    });
    Route::get('/trucks/{truck}', [TruckController::class, 'show'])->name('trucks.show');
    Route::get('/trips/estimate-km', \App\Http\Controllers\TripEstimateController::class)->name('trips.estimate-km');
    Route::resource('trips', TripController::class);
    Route::post('/trips/{trip}/start', [TripController::class, 'start'])->name('trips.start');
    Route::post('/trips/{trip}/end', [TripController::class, 'end'])->name('trips.end');

    Route::get('/trip-stops/create', [\App\Http\Controllers\TripStopController::class, 'create'])->name('trip-stops.create');
    Route::post('/trip-stops', [\App\Http\Controllers\TripStopController::class, 'store'])->name('trip-stops.store');
    Route::delete('/trip-stops/{tripStop}', [\App\Http\Controllers\TripStopController::class, 'destroy'])->name('trip-stops.destroy');

    Route::get('/fuel-expenses/create', [FuelExpenseController::class, 'create'])->name('fuel-expenses.create');
    Route::post('/fuel-expenses', [FuelExpenseController::class, 'store'])->name('fuel-expenses.store');
    Route::get('/fuel-expenses/{fuelExpense}/edit', [FuelExpenseController::class, 'edit'])->name('fuel-expenses.edit');
    Route::put('/fuel-expenses/{fuelExpense}', [FuelExpenseController::class, 'update'])->name('fuel-expenses.update');
    Route::delete('/fuel-expenses/{fuelExpense}', [FuelExpenseController::class, 'destroy'])->name('fuel-expenses.destroy');

    Route::get('/other-expenses/create', [OtherExpenseController::class, 'create'])->name('other-expenses.create');
    Route::post('/other-expenses', [OtherExpenseController::class, 'store'])->name('other-expenses.store');
    Route::get('/other-expenses/{otherExpense}/edit', [OtherExpenseController::class, 'edit'])->name('other-expenses.edit');
    Route::put('/other-expenses/{otherExpense}', [OtherExpenseController::class, 'update'])->name('other-expenses.update');
    Route::delete('/other-expenses/{otherExpense}', [OtherExpenseController::class, 'destroy'])->name('other-expenses.destroy');

    Route::get('/incidents/create', [IncidentController::class, 'create'])->name('incidents.create');
    Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
    Route::get('/incidents/{incident}/edit', [IncidentController::class, 'edit'])->name('incidents.edit');
    Route::put('/incidents/{incident}', [IncidentController::class, 'update'])->name('incidents.update');
    Route::delete('/incidents/{incident}', [IncidentController::class, 'destroy'])->name('incidents.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
