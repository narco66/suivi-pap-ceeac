<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixCurrentUserAdmin extends Command
{
    protected $signature = 'admin:fix-current {email=admin@ceeac.int}';
    protected $description = 'Corriger immédiatement les permissions de l\'utilisateur admin';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("🔧 Correction immédiate des permissions pour {$email}...");
        $this->newLine();
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Utilisateur {$email} non trouvé.");
            return 1;
        }

        // 1. Créer le rôle admin_dsi s'il n'existe pas
        $adminDsiRole = Role::firstOrCreate(['name' => 'admin_dsi']);
        $this->info("✓ Rôle admin_dsi prêt");

        // 2. Récupérer TOUTES les permissions
        $allPermissions = Permission::all();
        $this->info("✓ {$allPermissions->count()} permissions trouvées");

        // 3. Assigner TOUTES les permissions au rôle admin_dsi
        $adminDsiRole->syncPermissions($allPermissions);
        $this->info("✓ Toutes les permissions assignées au rôle admin_dsi");

        // 4. Assigner le rôle admin_dsi à l'utilisateur
        $user->syncRoles(['admin_dsi']);
        $this->info("✓ Rôle admin_dsi assigné à l'utilisateur");

        // 5. Activer l'utilisateur
        $user->update(['actif' => true]);
        $this->info("✓ Utilisateur activé");

        // 6. Vider le cache
        $this->info("✓ Vidage du cache...");
        \Artisan::call('permission:cache-reset');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('cache:clear');

        // 7. Vérification
        $this->newLine();
        $this->info("📊 Vérification finale:");
        $this->line("  - Rôles: " . $user->fresh()->getRoleNames()->implode(', '));
        $this->line("  - Permissions totales: " . $user->fresh()->getAllPermissions()->count());
        $this->line("  - hasRole(admin_dsi): " . ($user->fresh()->hasRole('admin_dsi') ? '✅' : '❌'));
        $this->line("  - can(admin.access): " . ($user->fresh()->can('admin.access') ? '✅' : '❌'));
        $this->line("  - can(viewAny admin.user): " . ($user->fresh()->can('viewAny admin.user') ? '✅' : '❌'));

        $this->newLine();
        $this->info("✅ Correction terminée!");
        $this->info("   Déconnectez-vous et reconnectez-vous pour que les changements prennent effet.");
        
        return 0;
    }
}


