<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Papa\PapaController;
use App\Http\Controllers\Papa\ObjectifController;
use App\Http\Controllers\Papa\ActionPrioritaireController;
use App\Http\Controllers\Papa\TacheController;
use App\Http\Controllers\Papa\KpiController;
use App\Http\Controllers\Papa\AlerteController;
use App\Http\Controllers\Papa\AvancementController;
use App\Http\Controllers\Referentiel\CommissaireController;
use App\Http\Controllers\Referentiel\CommissionController;
use App\Http\Controllers\Referentiel\DepartementController;
use App\Http\Controllers\Referentiel\DirectionAppuiController;
use App\Http\Controllers\Referentiel\DirectionTechniqueController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

// Routes publiques - Landing
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/ressources', [\App\Http\Controllers\RessourceController::class, 'index'])->name('ressources');
Route::get('/ressources/{ressource}', [\App\Http\Controllers\RessourceController::class, 'show'])->name('ressources.show');
Route::get('/ressources/{ressource}/download', [\App\Http\Controllers\RessourceController::class, 'download'])->name('ressources.download');
Route::get('/docs', [LandingController::class, 'docs'])->name('docs');
Route::get('/status', [LandingController::class, 'status'])->name('status');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Modules PAPA
    Route::resource('papa', PapaController::class);
    Route::resource('objectifs', ObjectifController::class);
    Route::resource('actions-prioritaires', ActionPrioritaireController::class);
    Route::resource('taches', TacheController::class);
    Route::resource('kpi', KpiController::class);
    Route::resource('alertes', AlerteController::class);
    Route::resource('avancements', AvancementController::class);
    
    // Gantt Chart - Phase 1 MVP
    Route::get('gantt', [\App\Http\Controllers\Papa\GanttController::class, 'index'])->name('gantt.index');
    
    // API Gantt
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('projects/{papa}/gantt', [\App\Http\Controllers\Api\GanttApiController::class, 'show'])->name('gantt.show');
        Route::post('projects/{papa}/gantt/tasks', [\App\Http\Controllers\Api\GanttTaskController::class, 'store'])->name('gantt.tasks.store');
        Route::put('gantt/tasks/{tache}', [\App\Http\Controllers\Api\GanttTaskController::class, 'update'])->name('gantt.tasks.update');
        Route::delete('gantt/tasks/{tache}', [\App\Http\Controllers\Api\GanttTaskController::class, 'destroy'])->name('gantt.tasks.destroy');
        Route::post('projects/{papa}/gantt/sync', [\App\Http\Controllers\Api\GanttSyncController::class, 'sync'])->name('gantt.sync');
    });
    
    // Référentiels
    Route::resource('commissaires', CommissaireController::class);
    Route::resource('commissions', CommissionController::class);
    Route::resource('departements', DepartementController::class);
    Route::resource('directions-appui', DirectionAppuiController::class);
    Route::resource('directions-techniques', DirectionTechniqueController::class);
    
    // Import/Export
    Route::get('import', [ImportController::class, 'index'])->name('import.index');
    Route::post('import', [ImportController::class, 'store'])->name('import.store');
    Route::get('export', [ExportController::class, 'index'])->name('export.index');
    Route::post('export', [ExportController::class, 'export'])->name('export.export');
    
    // Fichiers sécurisés
    Route::get('files/{path}', [FileController::class, 'serve'])->where('path', '.*')->name('files.serve');
    
    // Administration (protégé par permissions)
    Route::prefix('admin')->name('admin.')->middleware(['admin.access'])->group(function () {
        // Utilisateurs
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::post('users/{user}/activate', [\App\Http\Controllers\Admin\UserController::class, 'activate'])->name('users.activate');
        Route::post('users/{user}/suspend', [\App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('users.suspend');
        
        // Rôles & Permissions
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        
        // Structures
        Route::resource('structures', \App\Http\Controllers\Admin\StructureController::class);
        
        // Paramètres
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::get('settings/{group}/edit', [\App\Http\Controllers\Admin\SettingController::class, 'editGroup'])->name('settings.edit-group');
        Route::put('settings/{group}', [\App\Http\Controllers\Admin\SettingController::class, 'updateGroup'])->name('settings.update-group');
        Route::put('settings/{setting}', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        
        // Audit
        Route::get('audit', [\App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audit.index');
        Route::get('audit/{auditLog}', [\App\Http\Controllers\Admin\AuditController::class, 'show'])->name('audit.show');
        Route::get('audit/export', [\App\Http\Controllers\Admin\AuditController::class, 'export'])->name('audit.export');
        
        // Santé Système
        Route::get('system/health', [\App\Http\Controllers\Admin\SystemHealthController::class, 'index'])->name('system.health');
        
        // Ressources (gestion admin)
        Route::resource('ressources', \App\Http\Controllers\Admin\RessourceAdminController::class);
        Route::post('ressources/generate-guide', [\App\Http\Controllers\Admin\GenerateGuideController::class, 'generateImportGuide'])->name('ressources.generate-guide');
    });
});

// Route publique pour les fichiers (si nécessaire, mais sécurisée)
Route::get('storage/{path}', function ($path) {
    // Bloquer les chemins absolus Windows ou Unix
    if (preg_match('/^[A-Z]:\\\\|^\/[^\/]|^\.\.|^C:/', $path)) {
        abort(403, 'Accès interdit : chemin invalide');
    }
    
    // Si le chemin commence par storage/, on le retire
    $path = str_replace('storage/', '', $path);
    
    // Vérifier que le fichier existe dans le storage public
    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
        abort(404, 'Fichier non trouvé');
    }
    
    // Retourner le fichier
    return response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($path));
})->where('path', '.*')->name('storage.serve');

require __DIR__.'/auth.php';
