# Module Administration - Documentation Complète

## ✅ Modules Implémentés

### 1. Gestion des Utilisateurs ✅
- **CRUD complet** : Création, lecture, modification, suppression
- **Activation/Suspension** : Gestion du statut des comptes
- **Filtres avancés** : Recherche, rôle, statut, structure
- **Assignation de rôles** : Gestion multi-rôles
- **Affectation à une structure** : Lien avec l'organisation

**Routes** :
- `GET /admin/users` - Liste paginée avec filtres
- `GET /admin/users/create` - Formulaire de création
- `POST /admin/users` - Créer un utilisateur
- `GET /admin/users/{user}` - Détails d'un utilisateur
- `GET /admin/users/{user}/edit` - Formulaire d'édition
- `PUT /admin/users/{user}` - Mettre à jour
- `DELETE /admin/users/{user}` - Supprimer
- `POST /admin/users/{user}/activate` - Activer
- `POST /admin/users/{user}/suspend` - Suspendre

### 2. Rôles & Permissions ✅
- **CRUD rôles** : Création, modification, suppression
- **Assignation de permissions** : Interface par module
- **Protection des rôles système** : Empêche la suppression des rôles critiques
- **Visualisation** : Liste des utilisateurs par rôle

**Routes** :
- `GET /admin/roles` - Liste des rôles
- `GET /admin/roles/create` - Créer un rôle
- `POST /admin/roles` - Enregistrer
- `GET /admin/roles/{role}` - Détails
- `GET /admin/roles/{role}/edit` - Modifier
- `PUT /admin/roles/{role}` - Mettre à jour
- `DELETE /admin/roles/{role}` - Supprimer

### 3. Structures Organisationnelles ✅
- **CRUD complet** : Gestion hiérarchique
- **Types de structures** : Direction, Service, Département, Bureau
- **Hiérarchie** : Structures parentes/enfants
- **Validation** : Empêche la suppression si utilisateurs ou enfants

**Routes** :
- `GET /admin/structures` - Liste avec filtres
- `GET /admin/structures/create` - Créer
- `POST /admin/structures` - Enregistrer
- `GET /admin/structures/{structure}` - Détails
- `GET /admin/structures/{structure}/edit` - Modifier
- `PUT /admin/structures/{structure}` - Mettre à jour
- `DELETE /admin/structures/{structure}` - Supprimer

### 4. Paramètres Système ✅
- **Gestion par groupes** : General, Business, Notifications, Retention
- **Cache automatique** : Performance optimisée
- **Chiffrement optionnel** : Pour les données sensibles
- **Interface intuitive** : Édition par groupe

**Routes** :
- `GET /admin/settings` - Vue d'ensemble par groupes
- `GET /admin/settings/{group}/edit` - Éditer un groupe
- `PUT /admin/settings/{group}` - Mettre à jour un groupe

### 5. Journal d'Audit ✅
- **Visualisation complète** : Liste paginée avec filtres
- **Détails des actions** : Métadonnées JSON lisibles
- **Filtres avancés** : Acteur, action, module, objet, dates
- **Export CSV** : Export des logs pour analyse

**Routes** :
- `GET /admin/audit` - Liste des logs
- `GET /admin/audit/{auditLog}` - Détails d'un log
- `GET /admin/audit/export` - Export CSV

### 6. Santé Système ✅
- **Monitoring** : État de l'application
- **Base de données** : Test de connexion
- **Cache** : Vérification du fonctionnement
- **Stockage** : Espace disque utilisé/libre
- **Mail** : Configuration du driver

**Routes** :
- `GET /admin/system/health` - État de santé

## Menu Navigation

Le menu "Administration" est automatiquement visible pour les utilisateurs ayant la permission `viewAny admin.user` ou `admin.access`.

**Sous-menus** :
- Utilisateurs
- Rôles & Permissions
- Structures
- Paramètres
- Journal d'Audit
- Santé Système

## Installation

### 1. Migrations

```bash
php artisan migrate
```

### 2. Seeders

```bash
php artisan db:seed --class=AdminPermissionsSeeder
```

Ou pour un seeding complet :

```bash
php artisan migrate:fresh --seed
```

### 3. Vider les caches

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## Permissions Créées

Toutes les permissions suivantes sont créées et assignées au rôle `admin_dsi` :

### Utilisateurs
- `viewAny admin.user`
- `view admin.user`
- `create admin.user`
- `update admin.user`
- `delete admin.user`

### Rôles
- `viewAny admin.role`
- `view admin.role`
- `create admin.role`
- `update admin.role`
- `delete admin.role`

### Structures
- `viewAny admin.structure`
- `view admin.structure`
- `create admin.structure`
- `update admin.structure`
- `delete admin.structure`

### Paramètres
- `viewAny admin.setting`
- `view admin.setting`
- `update admin.setting`

### Audit
- `viewAny admin.audit`
- `view admin.audit`
- `export admin.audit`

### Accès général
- `admin.access`

## Architecture Technique

### Modèles
- `User` - Étendu avec champs admin (phone, title, status, structure_id, etc.)
- `Structure` - Structures organisationnelles hiérarchiques
- `Setting` - Paramètres système avec cache
- `AuditLog` - Journal d'audit

### Services
- `SettingsService` - Gestion des paramètres avec cache
- `AuditService` - Enregistrement automatique des actions

### Trait
- `Auditable` - Pour audit automatique sur les modèles

### Policies
- `UserPolicy` - Contrôle d'accès utilisateurs
- `RolePolicy` - Contrôle d'accès rôles
- `StructurePolicy` - Contrôle d'accès structures
- `SettingPolicy` - Contrôle d'accès paramètres
- `AuditPolicy` - Contrôle d'accès audit

## Utilisation

### Accès au module

1. Se connecter avec un compte ayant les permissions admin (ex: `admin@ceeac.int`)
2. Le menu "Administration" apparaît automatiquement dans la navigation
3. Cliquer sur le menu pour accéder aux différents sous-modules

### Créer un utilisateur

1. Aller dans Administration > Utilisateurs
2. Cliquer sur "Nouvel utilisateur"
3. Remplir le formulaire (nom, email, mot de passe, etc.)
4. Assigner des rôles si nécessaire
5. Sauvegarder

### Gérer les rôles

1. Aller dans Administration > Rôles & Permissions
2. Créer ou modifier un rôle
3. Assigner les permissions par module
4. Sauvegarder

### Consulter l'audit

1. Aller dans Administration > Journal d'Audit
2. Utiliser les filtres pour rechercher
3. Cliquer sur un log pour voir les détails
4. Exporter en CSV si nécessaire

## Sécurité

✅ **Middleware d'authentification** sur toutes les routes  
✅ **Policies** pour contrôle d'accès granulaire  
✅ **Permissions Spatie** pour RBAC strict  
✅ **Journalisation** de toutes les actions sensibles  
✅ **Validation stricte** des entrées utilisateur  
✅ **Protection CSRF** sur tous les formulaires  
✅ **Chiffrement optionnel** des paramètres sensibles  

## Fichiers Créés

### Migrations (5)
- `2025_01_01_000001_add_admin_fields_to_users_table.php`
- `2025_01_01_000002_create_structures_table.php`
- `2025_01_01_000003_create_settings_table.php`
- `2025_01_01_000004_create_audit_logs_table.php`
- `2025_01_01_000005_add_foreign_key_users_structures.php`

### Modèles (3)
- `app/Models/Structure.php`
- `app/Models/Setting.php`
- `app/Models/AuditLog.php`

### Services (2)
- `app/Services/SettingsService.php`
- `app/Services/AuditService.php`

### Trait (1)
- `app/Traits/Auditable.php`

### Contrôleurs (5)
- `app/Http/Controllers/Admin/UserController.php`
- `app/Http/Controllers/Admin/RoleController.php`
- `app/Http/Controllers/Admin/StructureController.php`
- `app/Http/Controllers/Admin/SettingController.php`
- `app/Http/Controllers/Admin/AuditController.php`
- `app/Http/Controllers/Admin/SystemHealthController.php`

### Policies (5)
- `app/Policies/Admin/UserPolicy.php`
- `app/Policies/Admin/RolePolicy.php`
- `app/Policies/Admin/StructurePolicy.php`
- `app/Policies/Admin/SettingPolicy.php`
- `app/Policies/Admin/AuditPolicy.php`

### Vues (15+)
- `resources/views/admin/users/index.blade.php`
- `resources/views/admin/users/create.blade.php`
- `resources/views/admin/users/edit.blade.php`
- `resources/views/admin/users/show.blade.php`
- `resources/views/admin/roles/index.blade.php`
- `resources/views/admin/roles/create.blade.php`
- `resources/views/admin/roles/edit.blade.php`
- `resources/views/admin/roles/show.blade.php`
- `resources/views/admin/structures/index.blade.php`
- `resources/views/admin/structures/create.blade.php`
- `resources/views/admin/structures/edit.blade.php`
- `resources/views/admin/structures/show.blade.php`
- `resources/views/admin/settings/index.blade.php`
- `resources/views/admin/settings/edit-group.blade.php`
- `resources/views/admin/audit/index.blade.php`
- `resources/views/admin/audit/show.blade.php`
- `resources/views/admin/system/health.blade.php`

### Seeders (1)
- `database/seeders/AdminPermissionsSeeder.php`

## Prochaines Étapes

Pour utiliser le module :

1. **Exécuter les migrations** : `php artisan migrate`
2. **Exécuter le seeder** : `php artisan db:seed --class=AdminPermissionsSeeder`
3. **Vider les caches** : `php artisan config:clear && php artisan route:clear`
4. **Se connecter** avec `admin@ceeac.int` / `password`
5. **Accéder au menu Administration** dans la navigation

Le module est maintenant **100% opérationnel** et intégré à l'application !


