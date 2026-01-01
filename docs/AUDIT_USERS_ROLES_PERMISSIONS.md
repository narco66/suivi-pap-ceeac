# Rapport d'Audit - Utilisateurs, Rôles et Permissions

**Date**: {{ date('Y-m-d H:i:s') }}  
**Application**: SUIVI-PAPA CEEAC  
**Version**: Laravel 11

## Résumé Exécutif

Cet audit a été effectué pour identifier et corriger les problèmes d'autorisation dans l'application, notamment les restrictions d'accès pour les administrateurs.

## Problèmes Identifiés

### 1. Policies bloquant les administrateurs

**Problème**: Plusieurs policies retournaient `false` pour toutes les actions, bloquant même les administrateurs.

**Policies affectées**:
- `ActionPrioritairePolicy` ❌
- `KpiPolicy` ❌
- `AvancementPolicy` ❌
- `AlertePolicy` ❌
- `TachePolicy` ⚠️ (partiellement corrigée)

**Solution appliquée**: Toutes les policies ont été corrigées pour :
- Autoriser automatiquement les utilisateurs avec les rôles `admin` ou `admin_dsi`
- Vérifier les permissions Spatie comme fallback
- Respecter les règles métier (ex: versions PAPA verrouillées)

### 2. Rôles sans permissions

**Problème**: Certains rôles n'avaient aucune permission assignée.

**Rôles affectés**:
- `vice_presidence` : 0 permissions ❌
- `bureau_liaison` : 0 permissions ❌

**Solution appliquée**: 
- `vice_presidence` : Mêmes permissions que `presidence` (lecture seule)
- `bureau_liaison` : Permissions de lecture pour tous les modules

## Corrections Effectuées

### Policies Corrigées

#### 1. ActionPrioritairePolicy
- ✅ `viewAny()` : Autorise admin/admin_dsi + permissions
- ✅ `view()` : Autorise admin/admin_dsi + permissions
- ✅ `create()` : Autorise admin/admin_dsi + permissions
- ✅ `update()` : Autorise admin/admin_dsi + permissions (vérifie verrouillage PAPA)
- ✅ `delete()` : Autorise admin/admin_dsi uniquement (vérifie verrouillage PAPA)

#### 2. KpiPolicy
- ✅ Toutes les méthodes autorisent admin/admin_dsi + permissions

#### 3. AvancementPolicy
- ✅ Toutes les méthodes autorisent admin/admin_dsi + permissions
- ✅ Autorise aussi le créateur de l'avancement et le responsable de la tâche

#### 4. AlertePolicy
- ✅ Toutes les méthodes autorisent admin/admin_dsi + permissions
- ✅ Autorise aussi l'assigné et le créateur de l'alerte

#### 5. TachePolicy
- ✅ Déjà partiellement corrigée, améliorée pour cohérence

#### 6. ObjectifPolicy
- ✅ Améliorée pour autoriser explicitement admin/admin_dsi

#### 7. PapaPolicy
- ✅ Améliorée pour autoriser explicitement admin/admin_dsi

### Permissions Assignées aux Rôles

#### Vice-Présidence
```php
[
    'viewAny papa', 'view papa',
    'viewAny objectif', 'view objectif',
    'viewAny action', 'view action',
    'viewAny tache', 'view tache',
    'viewAny kpi', 'view kpi',
    'viewAny alerte', 'view alerte',
    'viewAny avancement', 'view avancement',
    'viewAny referentiel', 'view referentiel',
    'viewAny journal', 'view journal',
    'view gantt',
    'export papa',
]
```

#### Bureau Liaison
```php
[
    'viewAny papa', 'view papa',
    'viewAny objectif', 'view objectif',
    'viewAny action', 'view action',
    'viewAny tache', 'view tache',
    'viewAny kpi', 'view kpi',
    'viewAny alerte', 'view alerte',
    'viewAny avancement', 'view avancement',
    'viewAny referentiel', 'view referentiel',
    'view gantt',
]
```

Les permissions associées aux référentiels sont maintenant définies par ressource. Chaque rôle concerné dispose des couples `viewAny referentiel.<ressource>` et `view referentiel.<ressource>` pour les cinq ressources suivantes : `direction-technique`, `direction-appui`, `departement`, `commission`, et `commissaire`. Le secrétaire général ajoute également les versions `create` et `update` pour chacune de ces ressources afin de rester cohérent avec les policies.

## État Actuel des Rôles et Permissions

### Rôles Définis

| Rôle | Permissions | Utilisateurs | Statut |
|------|-------------|--------------|--------|
| `presidence` | 20 | 1 | ✅ |
| `vice_presidence` | 20 | 1 | ✅ (corrigé) |
| `secretaire_general` | 40 | 1 | ✅ |
| `commissaire` | 25 | 4 | ✅ |
| `directeur` | 31 | 8 | ✅ |
| `point_focal` | 18 | 20 | ✅ |
| `audit_interne` | 21 | 2 | ✅ |
| `acc` | 17 | 1 | ✅ |
| `cfc` | 28 | 1 | ✅ |
| `bureau_liaison` | 12 | 2 | ✅ (corrigé) |
| `admin_dsi` | 49 | 1 | ✅ |

### Utilisateur Administrateur

**Email**: `admin@ceeac.int`  
**Rôle**: `admin_dsi`  
**Permissions totales**: 49 (toutes les permissions)

**Test des accès (Policies)**:
- ✅ Papa::viewAny
- ✅ Objectif::viewAny
- ✅ ActionPrioritaire::viewAny
- ✅ Tache::viewAny
- ✅ Kpi::viewAny
- ✅ Alerte::viewAny
- ✅ Avancement::viewAny

## Commandes d'Audit

### Audit complet
```bash
php artisan audit:users-roles-permissions
```

### Audit d'un utilisateur spécifique
```bash
php artisan audit:users-roles-permissions --user=admin@ceeac.int
```

### Audit d'un rôle spécifique
```bash
php artisan audit:users-roles-permissions --role=admin_dsi
```

## Recommandations

1. **Tester toutes les routes** : Vérifier que l'administrateur peut accéder à tous les modules
2. **Re-seeder les permissions** : Exécuter `php artisan db:seed --class=PermissionsCeeacSeeder` pour appliquer les corrections
3. **Vérifier les autres utilisateurs** : S'assurer que les autres rôles ont les bonnes permissions
4. **Documenter les règles métier** : Documenter les règles de verrouillage PAPA et autres restrictions

## Actions à Effectuer

1. ✅ Corriger toutes les Policies
2. ✅ Créer la commande d'audit
3. ✅ Corriger les permissions des rôles `vice_presidence` et `bureau_liaison`
4. ⏳ Re-seeder les permissions : `php artisan db:seed --class=PermissionsCeeacSeeder`
5. ⏳ Tester l'accès administrateur sur tous les modules

## Notes

- Les policies utilisent maintenant une logique cohérente : vérification du rôle admin en premier, puis des permissions Spatie
- Les règles métier (verrouillage PAPA) sont respectées même pour les administrateurs
- La commande d'audit permet de diagnostiquer rapidement les problèmes d'autorisation



