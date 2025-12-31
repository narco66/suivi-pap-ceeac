<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class GrantFullAdminAccess extends Command
{
    protected $signature = 'admin:grant-full-access {email=admin@ceeac.int}';
    protected $description = 'Accorder tous les droits à un administrateur';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("🔐 Attribution de tous les droits à {$email}...");
        $this->newLine();
        
        DB::transaction(function () use ($email) {
            // 1. Récupérer ou créer l'utilisateur
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->warn("  ⚠️  Utilisateur {$email} non trouvé. Création...");
                $user = User::create([
                    'name' => 'Administrateur Système',
                    'email' => $email,
                    'password' => bcrypt('password'),
                    'fonction' => 'Administrateur',
                    'actif' => true,
                ]);
                $this->info("    ✓ Utilisateur créé");
            } else {
                $this->info("  ✓ Utilisateur trouvé: {$user->name}");
            }
            
            // 2. Créer ou récupérer le rôle admin_dsi (super admin)
            $adminDsiRole = Role::firstOrCreate(['name' => 'admin_dsi']);
            $this->info("  ✓ Rôle admin_dsi prêt");
            
            // 3. Récupérer TOUTES les permissions existantes
            $allPermissions = Permission::all();
            $this->info("  ✓ {$allPermissions->count()} permissions trouvées");
            
            // 4. Assigner TOUTES les permissions au rôle admin_dsi
            $adminDsiRole->syncPermissions($allPermissions);
            $this->info("  ✓ Toutes les permissions assignées au rôle admin_dsi");
            
            // 5. Assigner le rôle admin_dsi à l'utilisateur
            $user->syncRoles(['admin_dsi']);
            $this->info("  ✓ Rôle admin_dsi assigné à l'utilisateur");
            
            // 6. S'assurer que l'utilisateur est actif
            $user->update(['actif' => true]);
            $this->info("  ✓ Utilisateur activé");
            
            // 7. Vérification finale
            $this->newLine();
            $this->info("📊 Vérification finale:");
            $this->line("  - Nom: {$user->name}");
            $this->line("  - Email: {$user->email}");
            $this->line("  - Rôles: " . $user->getRoleNames()->implode(', '));
            $this->line("  - Permissions totales: " . $user->getAllPermissions()->count());
            $this->line("  - Actif: " . ($user->actif ? 'Oui' : 'Non'));
            
            // Vérifier quelques permissions clés
            $keyPermissions = [
                'admin.access',
                'viewAny admin.user',
                'viewAny admin.role',
                'viewAny admin.structure',
                'viewAny admin.setting',
                'viewAny admin.audit',
            ];
            
            $this->newLine();
            $this->info("🔑 Vérification des permissions clés:");
            foreach ($keyPermissions as $permission) {
                $has = $user->hasPermissionTo($permission);
                $icon = $has ? '✓' : '❌';
                $this->line("  {$icon} {$permission}");
            }
        });
        
        // 8. Vider le cache des permissions
        $this->newLine();
        $this->info("🧹 Vidage du cache des permissions...");
        \Artisan::call('permission:cache-reset');
        $this->info("  ✓ Cache vidé");
        
        $this->newLine();
        $this->info("✅ Tous les droits ont été accordés à {$email}!");
        $this->info("   L'utilisateur peut maintenant accéder à toutes les fonctionnalités.");
        
        return 0;
    }
}


