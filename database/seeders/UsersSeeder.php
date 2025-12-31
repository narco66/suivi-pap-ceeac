<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Departement;
use App\Models\DirectionTechnique;
use App\Models\DirectionAppui;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('seeding.volumes');
        $password = config('seeding.demo_passwords.default', 'password');
        
        $this->command->info('👥 Création des utilisateurs avec rôles...');
        
        DB::transaction(function () use ($config, $password) {
            // Créer les rôles s'ils n'existent pas
            $this->createRoles();
            
            // Présidence
            $this->command->info('  → Création des utilisateurs Présidence...');
            $presidents = $this->createUsers('presidence', $config['users_presidence'], [
                'name' => 'Président de la CEEAC',
                'email' => 'president@ceeac.int',
                'fonction' => 'Président',
            ]);
            $presidents->each(fn($u) => $u->assignRole('presidence'));
            $this->command->info("    ✓ {$presidents->count()} utilisateur(s) Présidence créé(s)");
            
            // Vice-Présidence
            $this->command->info('  → Création des utilisateurs Vice-Présidence...');
            $vicePresidents = $this->createUsers('vice_presidence', $config['users_vice_presidence'], [
                'name' => 'Vice-Président de la CEEAC',
                'email' => 'vice-president@ceeac.int',
                'fonction' => 'Vice-Président',
            ]);
            $vicePresidents->each(fn($u) => $u->assignRole('vice_presidence'));
            $this->command->info("    ✓ {$vicePresidents->count()} utilisateur(s) Vice-Présidence créé(s)");
            
            // Secrétaires Généraux
            $this->command->info('  → Création des Secrétaires Généraux...');
            $sgs = $this->createUsers('secretaire_general', $config['users_secretaires_generaux'], [
                'name' => 'Secrétaire Général',
                'email' => 'sg@ceeac.int',
                'fonction' => 'Secrétaire Général',
            ]);
            $sgs->each(fn($u) => $u->assignRole('secretaire_general'));
            $this->command->info("    ✓ {$sgs->count()} Secrétaire(s) Général(aux) créé(s)");
            
            // Commissaires
            $this->command->info('  → Création des Commissaires...');
            $commissaires = $this->createUsers('commissaire', $config['users_commissaires'], [
                'fonction' => 'Commissaire',
            ]);
            $commissaires->each(fn($u) => $u->assignRole('commissaire'));
            $this->command->info("    ✓ {$commissaires->count()} Commissaire(s) créé(s)");
            
            // Directeurs (Directions Techniques)
            $this->command->info('  → Création des Directeurs (Directions Techniques)...');
            $directionsTech = DirectionTechnique::all();
            $directeursTech = collect();
            foreach ($directionsTech->take($config['users_directeurs']) as $direction) {
                $user = User::factory()->create([
                    'name' => 'Directeur ' . $direction->libelle,
                    'email' => 'directeur.' . strtolower(str_replace(' ', '.', $direction->code)) . '@ceeac.int',
                    'fonction' => 'Directeur',
                ]);
                $user->assignRole('directeur');
                $directeursTech->push($user);
            }
            $this->command->info("    ✓ {$directeursTech->count()} Directeur(s) créé(s)");
            
            // Directeurs (Directions d'Appui)
            $this->command->info('  → Création des Directeurs (Directions d\'Appui)...');
            $directionsAppui = DirectionAppui::all();
            $directeursAppui = collect();
            foreach ($directionsAppui->take($config['users_directeurs'] - $directeursTech->count()) as $direction) {
                $user = User::factory()->create([
                    'name' => 'Directeur ' . $direction->libelle,
                    'email' => 'directeur.' . strtolower(str_replace(' ', '.', $direction->code)) . '@ceeac.int',
                    'fonction' => 'Directeur',
                ]);
                $user->assignRole('directeur');
                $directeursAppui->push($user);
            }
            $this->command->info("    ✓ {$directeursAppui->count()} Directeur(s) d'appui créé(s)");
            
            // Points focaux
            $this->command->info('  → Création des Points focaux...');
            $pointsFocaux = $this->createUsers('point_focal', $config['users_points_focaux'], [
                'fonction' => 'Point Focal',
            ]);
            $pointsFocaux->each(fn($u) => $u->assignRole('point_focal'));
            $this->command->info("    ✓ {$pointsFocaux->count()} Point(s) focal(aux) créé(s)");
            
            // Audit Interne
            $this->command->info('  → Création des utilisateurs Audit Interne...');
            $audit = $this->createUsers('audit_interne', $config['users_audit_interne'], [
                'name' => 'Auditeur Interne',
                'email' => 'audit@ceeac.int',
                'fonction' => 'Auditeur',
            ]);
            $audit->each(fn($u) => $u->assignRole('audit_interne'));
            $this->command->info("    ✓ {$audit->count()} utilisateur(s) Audit créé(s)");
            
            // ACC
            $this->command->info('  → Création des utilisateurs ACC...');
            $acc = $this->createUsers('acc', $config['users_acc'], [
                'name' => 'Agent ACC',
                'email' => 'acc@ceeac.int',
                'fonction' => 'Agent ACC',
            ]);
            $acc->each(fn($u) => $u->assignRole('acc'));
            $this->command->info("    ✓ {$acc->count()} utilisateur(s) ACC créé(s)");
            
            // CFC
            $this->command->info('  → Création des utilisateurs CFC...');
            $cfc = $this->createUsers('cfc', $config['users_cfc'], [
                'name' => 'Agent CFC',
                'email' => 'cfc@ceeac.int',
                'fonction' => 'Agent CFC',
            ]);
            $cfc->each(fn($u) => $u->assignRole('cfc'));
            $this->command->info("    ✓ {$cfc->count()} utilisateur(s) CFC créé(s)");
            
            // Bureau Liaison
            $this->command->info('  → Création des utilisateurs Bureau Liaison...');
            $bureauLiaison = $this->createUsers('bureau_liaison', $config['users_bureau_liaison'], [
                'fonction' => 'Agent Bureau Liaison',
            ]);
            $bureauLiaison->each(fn($u) => $u->assignRole('bureau_liaison'));
            $this->command->info("    ✓ {$bureauLiaison->count()} utilisateur(s) Bureau Liaison créé(s)");
            
            // Admin DSI
            $this->command->info('  → Création de l\'administrateur DSI...');
            $admin = User::factory()->create([
                'name' => 'Administrateur DSI',
                'email' => 'admin@ceeac.int',
                'fonction' => 'Administrateur Système',
                'password' => Hash::make($password),
            ]);
            // Assigner les deux rôles pour compatibilité maximale
            $admin->assignRole(['admin_dsi', 'admin']);
            $this->command->info("    ✓ 1 administrateur DSI créé (email: admin@ceeac.int, password: {$password})");
        });
        
        $this->command->info('✅ Utilisateurs créés avec succès!');
        $this->command->info("📧 Tous les utilisateurs ont le mot de passe: {$password}");
    }
    
    private function createUsers(string $prefix, int $count, array $defaults = []): \Illuminate\Support\Collection
    {
        $users = collect();
        for ($i = 1; $i <= $count; $i++) {
            // Si un email est fourni dans defaults et qu'on crée plusieurs utilisateurs, ajouter un numéro
            if (isset($defaults['email']) && $count > 1) {
                $email = str_replace('@ceeac.int', $i . '@ceeac.int', $defaults['email']);
            } else {
                $email = $defaults['email'] ?? ($prefix . $i . '@ceeac.int');
            }
            
            // Si un nom est fourni dans defaults et qu'on crée plusieurs utilisateurs, ajouter un numéro
            if (isset($defaults['name']) && $count > 1) {
                $name = $defaults['name'] . ' ' . $i;
            } else {
                $name = $defaults['name'] ?? ucfirst(str_replace('_', ' ', $prefix)) . ' ' . $i;
            }
            
            // Créer l'utilisateur sans l'email et le nom dans defaults pour éviter les doublons
            $userData = $defaults;
            unset($userData['email'], $userData['name']);
            
            $user = User::factory()->create(array_merge([
                'name' => $name,
                'email' => $email,
            ], $userData));
            
            $users->push($user);
        }
        return $users;
    }
    
    private function createRoles(): void
    {
        $roles = [
            'presidence',
            'vice_presidence',
            'secretaire_general',
            'commissaire',
            'directeur',
            'point_focal',
            'audit_interne',
            'acc',
            'cfc',
            'bureau_liaison',
            'admin_dsi',
        ];
        
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
    }
}

