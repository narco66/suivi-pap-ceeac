<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class FixGanttPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gantt:fix-permissions {email? : Email de l\'utilisateur (optionnel)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier et corriger les permissions Gantt pour un utilisateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Vérification des permissions Gantt...');

        // Récupérer tous les utilisateurs ou un utilisateur spécifique
        $email = $this->argument('email');
        
        if ($email) {
            $users = User::where('email', $email)->get();
        } else {
            $users = User::all();
        }

        if ($users->isEmpty()) {
            $this->error('Aucun utilisateur trouvé.');
            return 1;
        }

        $permissions = Permission::where('name', 'like', 'gantt.%')->pluck('name')->toArray();
        $adminRole = Role::where('name', 'admin')->first();
        $adminDsiRole = Role::where('name', 'admin_dsi')->first();

        foreach ($users as $user) {
            $this->line("\n👤 Utilisateur: {$user->email} (ID: {$user->id})");
            
            // Vérifier les rôles
            $roles = $user->roles->pluck('name')->toArray();
            $this->line("  Rôles: " . (empty($roles) ? 'Aucun' : implode(', ', $roles)));
            
            // Vérifier les permissions directes
            $userPerms = $user->permissions->pluck('name')->toArray();
            $ganttPerms = array_intersect($userPerms, $permissions);
            $this->line("  Permissions Gantt directes: " . (empty($ganttPerms) ? 'Aucune' : implode(', ', $ganttPerms)));
            
            // Vérifier si l'utilisateur peut voir le Gantt
            $canView = $user->hasPermissionTo('gantt.view') || 
                      $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager', 'direction_manager']);
            
            if (!$canView) {
                $this->warn("  ⚠ L'utilisateur n'a pas accès au Gantt");
                
                // Proposer de corriger
                if ($this->confirm("  Attribuer le rôle 'admin' à cet utilisateur ?", true)) {
                    if ($adminRole) {
                        $user->assignRole($adminRole);
                        $this->info("  ✓ Rôle 'admin' attribué");
                    } else {
                        $this->error("  ✗ Le rôle 'admin' n'existe pas");
                    }
                } elseif ($this->confirm("  Attribuer directement la permission 'gantt.view' ?", false)) {
                    $user->givePermissionTo('gantt.view');
                    $this->info("  ✓ Permission 'gantt.view' attribuée");
                }
            } else {
                $this->info("  ✓ L'utilisateur a accès au Gantt");
            }
        }

        // Vider le cache
        $this->call('permission:cache-reset');
        $this->info("\n✅ Vérification terminée !");
        
        return 0;
    }
}


