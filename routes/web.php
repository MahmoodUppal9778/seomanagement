<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ActivityLogController;

Route::get('/activity-logs', [ActivityLogController::class, 'index'])->middleware('auth')->name('activity.logs');
Route::get('upload', [SiteController::class, 'showUploadForm']);
Route::post('import', [SiteController::class, 'import'])->name('sites.import');
Route::get('/sites', [SiteController::class, 'index']);
Route::post('/apply-rules', [SiteController::class, 'applyRules'])->name('apply.rules');
Route::get('/applyRules', [SiteController::class, 'applyRuleBlade']);
Route::get('/export/excel', [SiteController::class, 'exportExcel'])->name('export.excel');
Route::get('/domain-matching', [SiteController::class, 'showDomainMatchingForm'])->name('domain.matching.form');
Route::post('/domain-matching', [SiteController::class, 'processDomainMatching'])->name('domain.matching.process');
Route::post('/sites/export-domain-matches', [SiteController::class, 'exportDomainMatches'])
    ->name('sites.export-domain-matches');
    
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
