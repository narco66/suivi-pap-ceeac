# Module Administration - SUIVI-PAPA CEEAC

## Vue d'ensemble

Le module Administration fournit une interface complète pour gérer les utilisateurs, rôles, permissions, paramètres système, structures organisationnelles et journalisation d'audit.

## Installation

### 1. Migrations

Exécuter les migrations pour créer les tables nécessaires :

```bash
php artisan migrate
```

Les migrations créées :
- `2025_01_01_000001_add_admin_fields_to_users_table.php` - Champs additionnels pour users
- `2025_01_01_000002_create_structures_table.php` - Table des structures organisationnelles
- `2025_01_01_000003_create_settings_table.php` - Table des paramètres système
- `2025_01_01_000004_create_audit_logs_table.php` - Table des journaux d'audit
- `2025_01_01_000005_add_foreign_key_users_structures.php` - Contrainte FK users->structures

### 2. Seeders

Créer et exécuter les seeders pour les permissions admin :

```bash
php artisan db:seed --class=AdminPermissionsSeeder
```

## Architecture

### Modèles

- **User** : Utilisateur avec champs additionnels (phone, title, status, structure_id, etc.)
- **Structure** : Structures organisationnelles hiérarchiques
- **Setting** : Paramètres système avec cache et chiffrement optionnel
- **AuditLog** : Journal d'audit pour toutes les actions

### Services

- **SettingsService** : Gestion des paramètres système avec cache
- **AuditService** : Enregistrement automatique des actions dans le journal d'audit

### Trait

- **Auditable** : Trait à ajouter aux modèles pour l'audit automatique

## Permissions

Les permissions suivantes sont requises :

### Utilisateurs
- `viewAny admin.user` - Voir la liste des utilisateurs
- `view admin.user` - Voir un utilisateur
- `create admin.user` - Créer un utilisateur
- `update admin.user` - Modifier un utilisateur
- `delete admin.user` - Supprimer un utilisateur

### Rôles & Permissions
- `viewAny admin.role` - Voir la liste des rôles
- `view admin.role` - Voir un rôle
- `create admin.role` - Créer un rôle
- `update admin.role` - Modifier un rôle
- `delete admin.role` - Supprimer un rôle

### Paramètres
- `viewAny admin.setting` - Voir les paramètres
- `update admin.setting` - Modifier les paramètres

### Audit
- `viewAny admin.audit` - Voir le journal d'audit
- `export admin.audit` - Exporter le journal d'audit

## Routes

Toutes les routes admin sont préfixées par `/admin` et protégées par le middleware `permission:viewAny admin.user`.

### Utilisateurs
- `GET /admin/users` - Liste des utilisateurs
- `GET /admin/users/create` - Formulaire de création
- `POST /admin/users` - Créer un utilisateur
- `GET /admin/users/{user}` - Détails d'un utilisateur
- `GET /admin/users/{user}/edit` - Formulaire d'édition
- `PUT/PATCH /admin/users/{user}` - Mettre à jour un utilisateur
- `DELETE /admin/users/{user}` - Supprimer un utilisateur
- `POST /admin/users/{user}/activate` - Activer un utilisateur
- `POST /admin/users/{user}/suspend` - Suspendre un utilisateur

### Rôles & Permissions
- `GET /admin/roles` - Liste des rôles
- `GET /admin/roles/create` - Formulaire de création
- `POST /admin/roles` - Créer un rôle
- `GET /admin/roles/{role}` - Détails d'un rôle
- `GET /admin/roles/{role}/edit` - Formulaire d'édition
- `PUT/PATCH /admin/roles/{role}` - Mettre à jour un rôle
- `DELETE /admin/roles/{role}` - Supprimer un rôle

### Structures
- `GET /admin/structures` - Liste des structures
- `GET /admin/structures/create` - Formulaire de création
- `POST /admin/structures` - Créer une structure
- `GET /admin/structures/{structure}` - Détails d'une structure
- `GET /admin/structures/{structure}/edit` - Formulaire d'édition
- `PUT/PATCH /admin/structures/{structure}` - Mettre à jour une structure
- `DELETE /admin/structures/{structure}` - Supprimer une structure

### Paramètres
- `GET /admin/settings` - Liste des paramètres par groupe
- `GET /admin/settings/{group}/edit` - Éditer un groupe de paramètres
- `PUT /admin/settings/{group}` - Mettre à jour un groupe de paramètres

### Audit
- `GET /admin/audit` - Liste des logs d'audit
- `GET /admin/audit/{auditLog}` - Détails d'un log
- `GET /admin/audit/export` - Exporter les logs (CSV)

### Santé Système
- `GET /admin/system/health` - État de santé de l'application

## Utilisation

### SettingsService

```php
use App\Services\SettingsService;

$settings = app(SettingsService::class);

// Récupérer une valeur
$appName = $settings->get('app.name', 'SUIVI-PAPA CEEAC');

// Définir une valeur
$settings->set('app.name', 'Nouveau nom', 'general', 'string');

// Récupérer un groupe
$generalSettings = $settings->getGroup('general');
```

### AuditService

```php
use App\Services\AuditService;

$audit = app(AuditService::class);

// Enregistrer une action
$audit->log('created', $user, ['email' => $user->email], 'admin', 'Création utilisateur');
```

### Trait Auditable

Ajouter le trait aux modèles pour l'audit automatique :

```php
use App\Traits\Auditable;

class MyModel extends Model
{
    use Auditable;
    
    // Les actions created, updated, deleted seront automatiquement enregistrées
}
```

## Comptes de démo

Après le seeding, les comptes suivants sont disponibles :

- **Admin DSI** : `admin@ceeac.int` / `Password@1234`
- **Secrétaire Général** : `sg@ceeac.int` / `Password@1234`

## Tests

Exécuter les tests :

```bash
php artisan test --filter Admin
```

## Modules Implémentés

✅ **Gestion des Utilisateurs** - CRUD complet avec activation/suspension
✅ **Rôles & Permissions** - CRUD complet avec assignation de permissions
✅ **Structures** - CRUD complet avec hiérarchie
✅ **Paramètres Système** - Gestion par groupes avec cache
✅ **Journal d'Audit** - Visualisation et export CSV
✅ **Santé Système** - Monitoring de l'état de l'application

## TODO / Améliorations futures

- [ ] Gestion des sessions utilisateur (liste, logout all)
- [ ] Réinitialisation de mot de passe par admin
- [ ] Upload d'avatar
- [ ] MFA (Multi-Factor Authentication)
- [ ] Gestion des clés API / tokens
- [ ] Tests Feature et Unit complets

## Sécurité

- Toutes les routes sont protégées par middleware d'authentification
- Contrôle d'accès via Policies et Permissions Spatie
- Journalisation de toutes les actions sensibles
- Protection CSRF sur tous les formulaires
- Validation stricte des entrées utilisateur
- Chiffrement optionnel des paramètres sensibles

## Support

Pour toute question ou problème, consulter la documentation Laravel et Spatie Permission.

