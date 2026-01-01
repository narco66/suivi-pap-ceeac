<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class FixAdminPermissions extends Command
{
    protected $signature = 'admin:fix-permissions {email=admin@ceeac.int}';
    protected $description = 'Corriger les permissions d\'un utilisateur admin';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("🔧 Correction des permissions pour {$email}...");
        
        // 1. S'assurer que les permissions admin existent
        $this->info("  → Vérification des permissions admin...");
        Artisan::call('db:seed', ['--class' => 'AdminPermissionsSeeder', '--force' => true]);
        $this->info("    ✓ Permissions admin créées/vérifiées");
        
        // 2. Récupérer ou créer l'utilisateur
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("  ❌ Utilisateur {$email} non trouvé.");
            $this->info("  💡 Création de l'utilisateur...");
            $user = User::create([
                'name' => 'Administrateur DSI',
                'email' => $email,
                'fonction' => 'Administrateur Système',
                'password' => bcrypt('password'),
            ]);
            $this->info("    ✓ Utilisateur créé");
        }
        
        // 3. S'assurer que les rôles existent
        $adminDsiRole = Role::firstOrCreate(['name' => 'admin_dsi']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // 4. Assigner toutes les permissions aux rôles
        $allPermissions = Permission::where('name', 'like', 'admin.%')
            ->orWhere('name', 'like', 'viewAny admin.%')
            ->orWhere('name', 'like', 'view admin.%')
            ->orWhere('name', 'like', 'create admin.%')
            ->orWhere('name', 'like', 'update admin.%')
            ->orWhere('name', 'like', 'delete admin.%')
            ->orWhere('name', 'like', 'export admin.%')
            ->orWhere('name', 'admin.access')
            ->get();
        
        $adminDsiRole->syncPermissions($allPermissions);
        $adminRole->syncPermissions($allPermissions);
        $this->info("    ✓ Permissions assignées aux rôles admin_dsi et admin");
        
        // 5. Assigner les rôles à l'utilisateur
        $user->syncRoles(['admin_dsi', 'admin']);
        $this->info("    ✓ Rôles assignés à l'utilisateur");
        
        // 6. Vider le cache des permissions
        Artisan::call('permission:cache-reset');
        $this->info("    ✓ Cache des permissions vidé");
        
        // 7. Vérification
        $this->newLine();
        $this->info("✅ Vérification finale:");
        $this->line("  - Rôles: " . $user->getRoleNames()->implode(', '));
        $this->line("  - Permissions totales: " . $user->getAllPermissions()->count());
        $this->line("  - admin.access: " . ($user->hasPermissionTo('admin.access') ? '✓' : '❌'));
        $this->line("  - viewAny admin.user: " . ($user->hasPermissionTo('viewAny admin.user') ? '✓' : '❌'));
        $this->line("  - hasRole admin_dsi: " . ($user->hasRole('admin_dsi') ? '✓' : '❌'));
        $this->line("  - hasRole admin: " . ($user->hasRole('admin') ? '✓' : '❌'));
        
        $this->newLine();
        $this->info("✅ Permissions corrigées avec succès!");
        $this->info("   Vous pouvez maintenant vous connecter avec {$email} / password");
        
        return 0;
    }
}



