<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Papa;
use App\Models\Objectif;
use App\Models\ActionPrioritaire;
use App\Models\Tache;
use App\Models\Kpi;
use App\Models\Alerte;
use App\Models\Avancement;
use App\Policies\PapaPolicy;
use App\Policies\ObjectifPolicy;
use App\Policies\ActionPrioritairePolicy;
use App\Policies\TachePolicy;
use App\Policies\Admin\UserPolicy;
use App\Policies\Admin\RolePolicy;
use App\Policies\Admin\StructurePolicy;
use App\Policies\Admin\SettingPolicy;
use App\Policies\Admin\AuditPolicy;
use App\Models\User;
use App\Models\Structure;
use App\Models\Setting;
use App\Models\AuditLog;
use App\Models\Ressource;
use App\Models\Commissaire;
use App\Models\Commission;
use App\Models\Departement;
use App\Models\DirectionTechnique;
use App\Models\DirectionAppui;
use Spatie\Permission\Models\Role;
use App\Policies\KpiPolicy;
use App\Policies\AlertePolicy;
use App\Policies\AvancementPolicy;
use App\Policies\RessourcePolicy;
use App\Policies\CommissairePolicy;
use App\Policies\CommissionPolicy;
use App\Policies\DepartementPolicy;
use App\Policies\DirectionTechniquePolicy;
use App\Policies\DirectionAppuiPolicy;
use App\Policies\GanttTaskPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Papa::class => PapaPolicy::class,
        Objectif::class => ObjectifPolicy::class,
        ActionPrioritaire::class => ActionPrioritairePolicy::class,
        Tache::class => TachePolicy::class,
        Kpi::class => KpiPolicy::class,
        Alerte::class => AlertePolicy::class,
        Avancement::class => AvancementPolicy::class,
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Structure::class => StructurePolicy::class,
        Setting::class => SettingPolicy::class,
        AuditLog::class => AuditPolicy::class,
        Ressource::class => RessourcePolicy::class,
        Commissaire::class => CommissairePolicy::class,
        Commission::class => CommissionPolicy::class,
        Departement::class => DepartementPolicy::class,
        DirectionTechnique::class => DirectionTechniquePolicy::class,
        DirectionAppui::class => DirectionAppuiPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrer les policies
        $this->registerPolicies();
        
        // Utiliser Bootstrap 5 pour la pagination
        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
    
    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
        
        // Enregistrer les méthodes spécifiques Gantt
        $ganttPolicy = new \App\Policies\GanttTaskPolicy();
        Gate::define('viewGantt', function ($user) use ($ganttPolicy) {
            return $ganttPolicy->viewGantt($user);
        });
        Gate::define('editDates', [\App\Policies\GanttTaskPolicy::class, 'editDates']);
        Gate::define('manageDependencies', [\App\Policies\GanttTaskPolicy::class, 'manageDependencies']);
        Gate::define('exportGantt', [\App\Policies\GanttTaskPolicy::class, 'export']);
    }
}
