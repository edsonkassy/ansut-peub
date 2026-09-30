<?php

use App\Http\Controllers\Admin\PalmaresController;
use App\Http\Controllers\LandingMapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Palmarès BAC (chargé depuis bootstrap/app.php, groupe middleware « web »)
|--------------------------------------------------------------------------
*/

// Données des pastilles de la carte de la landing (public, sans contact ni données sensibles)
Route::get('/landing/cohorte/{annee}', [LandingMapController::class, 'index'])
    ->whereNumber('annee')
    ->name('landing.cohorte');

// Espace admin (mêmes garde-fous que la gestion des bacheliers)
Route::middleware(['auth', 'verified', 'role:admin', 'admin.no_mobile', 'admin.permission:users.bacheliers.view'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('palmares', [PalmaresController::class, 'index'])->name('palmares.index');
        Route::get('palmares/export', [PalmaresController::class, 'export'])->name('palmares.export');
        Route::get('palmares/carte-data', [PalmaresController::class, 'carteData'])->name('palmares.carte-data');
    });
