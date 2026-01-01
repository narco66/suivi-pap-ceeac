# AUDIT SECRÉTAIRE GÉNÉRAL / DIRECTIONS D'APPUI
## Conformité à la Règle Métier

**Date** : 2025-01-02  
**Auditeur** : Lead Engineer Laravel 11 + Expert RBAC  
**Application** : SUIVI-PAP-CEEAC  
**Version Laravel** : 11.47.0

---

## 📋 RÈGLE MÉTIER À RESPECTER

1. **Le Secrétaire Général (SG) est l'autorité hiérarchique de TOUTES les Directions d'Appui et de Soutien.**
2. **Les Directions d'Appui (ex. : RH, Finances, Logistique, Informatique, Moyens Généraux, etc.) relèvent toutes du périmètre du SG.**
3. **Le SG n'est PAS Commissaire et n'intervient PAS dans le pilotage sectoriel des Départements techniques.**
4. **Le périmètre fonctionnel du SG couvre EXCLUSIVEMENT les Directions d'Appui.**
5. **Dans le système, le SG dispose :**
   - d'une vue consolidée et transversale sur l'ensemble des actions des Directions d'Appui ;
   - d'indicateurs globaux d'appui et de soutien (agrégés par direction et consolidés) ;
   - de capacités de validation, d'arbitrage et de coordination sur toutes les Directions d'Appui ;
   - d'un suivi transversal des risques, retards et goulots d'étranglement liés aux Directions d'Appui.

**PRINCIPE DE SÉCURITÉ** : "Deny by default" - toute donnée hors Directions d'Appui est strictement interdite au SG.

---

## 🔍 ÉTAPE A : INVENTAIRE & DIAGNOSTIC

### 1. Schéma Actuel des Tables et Relations

#### 1.1 Table `directions_appui`
```sql
- id (PK)
- code (UNIQUE)
- libelle
- description
- actif (boolean)
- timestamps
```

**✅ BON** : Table dédiée existe.  
**❌ PROBLÈME** : Aucune relation avec `users` (pas de `secretaire_general_user_id`).  
**❌ PROBLÈME** : Aucun champ `type` ou `parent_authority` pour identifier le rattachement au SG.

#### 1.2 Table `directions_techniques`
```sql
- id (PK)
- code (UNIQUE)
- libelle
- departement_id (FK → departements)
- description
- actif (boolean)
- timestamps
```

**✅ BON** : Table séparée des Directions d'Appui.  
**✅ BON** : Relation avec `departements` (sous Commissaire).

#### 1.3 Table `actions_prioritaires`
```sql
- id (PK)
- objectif_id (FK → objectifs)
- direction_technique_id (FK → directions_techniques) ✅
- direction_appui_id (FK → directions_appui) ✅
- type (string: 'technique', 'appui', 'administratif', 'autre')
- ...
```

**✅ BON** : Les actions peuvent être liées soit à une Direction Technique, soit à une Direction d'Appui.  
**❌ PROBLÈME** : Aucune contrainte empêchant une action d'avoir les deux (technique ET appui).  
**❌ PROBLÈME** : Le champ `type` n'est pas utilisé pour filtrer systématiquement.

#### 1.4 Table `users`
```sql
- id (PK)
- name
- email
- ...
```

**❌ PROBLÈME** : Aucune relation avec `directions_appui`.  
**❌ PROBLÈME** : Pas de méthode `isSecretaireGeneral()` ou `getAppuiDirections()`.

### 2. Relations Eloquent Actuelles

#### 2.1 Modèle `DirectionAppui`
```php
// app/Models/DirectionAppui.php
// ❌ AUCUNE relation définie
// ❌ Pas de scope forAppui()
// ❌ Pas de méthode isAppui()
```

#### 2.2 Modèle `DirectionTechnique`
```php
// app/Models/DirectionTechnique.php
public function departement()
{
    return $this->belongsTo(Departement::class);
}
```

**✅ BON** : Relation avec département existe.

#### 2.3 Modèle `ActionPrioritaire`
```php
// app/Models/ActionPrioritaire.php
// ❌ MANQUE :
// - public function directionAppui() → belongsTo(DirectionAppui::class)
// - public function isAppui() → bool
// - public function isTechnique() → bool
// - public function scopeForAppui($query)
// - public function scopeForTechnique($query)
```

**❌ PROBLÈME CRITIQUE** : Pas de relation `directionAppui()` définie.  
**❌ PROBLÈME CRITIQUE** : Pas de scope `forAppui()` pour filtrer les actions d'appui.

#### 2.4 Modèle `User`
```php
// app/Models/User.php
// ❌ MANQUE :
// - public function isSecretaireGeneral() → bool
// - public function getAppuiDirections() → Collection
```

### 3. Représentation "Secrétaire Général" dans le Système

**ACTUELLEMENT** :
- ✅ Rôle Spatie `'secretaire_general'` existe (voir `RolesCeeacSeeder`)
- ❌ Mais aucun lien entre `User` et `Directions d'Appui`
- ❌ Les permissions du SG sont trop larges (accès à TOUT, pas seulement APPUI)
- ❌ Aucune distinction fonctionnelle entre SG et autres rôles pour les Directions d'Appui

**PERMISSIONS ACTUELLES DU SG** (voir `PermissionsCeeacSeeder.php` ligne 182) :
```php
$sg->givePermissionTo([
    'viewAny papa', 'view papa', 'create papa', 'update papa', // ❌ TROP LARGE
    'viewAny action', 'view action', 'create action', 'update action', // ❌ TROP LARGE
    // ... accès à TOUT, y compris les Directions Techniques
]);
```

**❌ PROBLÈME CRITIQUE** : Le SG a accès à TOUTES les actions, y compris les Directions Techniques.

### 4. Contraintes et Validations

**❌ AUCUNE CONTRAINTE** :
- Pas de contrainte DB empêchant une action d'avoir `direction_technique_id` ET `direction_appui_id`
- Pas de validation FormRequest vérifiant que le SG ne peut créer/modifier que des actions d'appui
- Pas de contrainte empêchant d'affecter une Direction d'Appui à un Commissaire

### 5. Écrans/Endpoints Utilisés par le SG

#### 5.1 Endpoints Existants (NON SCOPPÉS)

| Endpoint | Controller | Méthode | Scope APPUI ? |
|----------|------------|---------|---------------|
| `/dashboard` | `DashboardController::index()` | ❌ NON | Statistiques GLOBALES (toutes actions) |
| `/actions-prioritaires` | `ActionPrioritaireController::index()` | ❌ NON | Récupère TOUTES les actions |
| `/actions-prioritaires/{id}` | `ActionPrioritaireController::show()` | ❌ NON | Aucune vérification APPUI |
| `/kpi` | `KpiController::index()` | ❌ NON | Tous les KPIs |
| `/alertes` | `AlerteController::index()` | ❌ NON | Toutes les alertes |
| `/taches` | `TacheController::index()` | ❌ NON | Toutes les tâches |

#### 5.2 Endpoints Manquants (À CRÉER)

| Endpoint | Description | Statut |
|----------|-------------|--------|
| `/secretaire-general/dashboard` | Dashboard transversal Appui | ❌ MANQUANT |
| `/secretaire-general/actions` | Vue consolidée actions d'appui | ❌ MANQUANT |
| `/secretaire-general/indicateurs` | Indicateurs transversaux | ❌ MANQUANT |
| `/secretaire-general/risques` | Suivi risques/retards Appui | ❌ MANQUANT |
| `/secretaire-general/validations` | Validation/arbitrage Appui | ❌ MANQUANT |

### 6. Failles de Sécurité Identifiées

#### 6.1 Data Leakage : Accès aux Directions Techniques

**🔴 CRITIQUE** : `DashboardController::index()`
```php
// Lignes 28-29
'actions_total' => ActionPrioritaire::count(), // ❌ GLOBAL (technique + appui)
'actions_en_cours' => ActionPrioritaire::whereIn(...)->count(), // ❌ GLOBAL
```

**🔴 CRITIQUE** : `ActionPrioritaireController::index()`
```php
// Ligne 18-23
$query = ActionPrioritaire::with([...]);
// ❌ Aucun filtre par direction_appui_id
// Le SG peut voir TOUTES les actions, y compris les Directions Techniques
```

**🔴 CRITIQUE** : `PermissionsCeeacSeeder.php`
```php
// Ligne 182-194
$sg->givePermissionTo([
    'viewAny action', 'view action', 'create action', 'update action',
    // ❌ Permissions trop larges : accès à TOUTES les actions
]);
```

#### 6.2 Queries Globales Sans Filtre APPUI

**Fichiers Impactés** :
- `app/Http/Controllers/DashboardController.php` : Lignes 28-42 (statistiques globales)
- `app/Http/Controllers/Papa/ActionPrioritaireController.php` : Ligne 18 (query sans filtre)
- `app/Http/Controllers/Papa/KpiController.php` : Ligne 17 (query sans filtre)
- `app/Http/Controllers/Papa/TacheController.php` : Ligne 17 (query sans filtre)
- `app/Http/Controllers/Papa/AlerteController.php` : Lignes 63, 73 (queries sans filtre)
- `app/Http/Controllers/ExportController.php` : Lignes 53, 57 (exports sans filtre)

**Exemple Typique** :
```php
// ❌ MAUVAIS
$actions = ActionPrioritaire::all();

// ✅ BON (à implémenter)
$actions = ActionPrioritaire::forAppui()->get();
```

#### 6.3 Policies Absentes ou Incomplètes

**Policies Existantes** :
- ✅ `ActionPrioritairePolicy` existe
- ✅ `KpiPolicy` existe
- ✅ `TachePolicy` existe
- ✅ `AlertePolicy` existe

**❌ PROBLÈME** : Aucune policy ne vérifie le scope APPUI pour le SG.

**Exemple de Correction Nécessaire** :
```php
// ❌ ACTUEL
public function view(User $user, ActionPrioritaire $action): bool
{
    if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
        return true;
    }
    return $user->hasPermissionTo('view action');
}

// ✅ CORRIGÉ
public function view(User $user, ActionPrioritaire $action): bool
{
    if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
        return true;
    }
    
    // SG : peut voir uniquement les actions d'appui
    if ($user->hasRole('secretaire_general')) {
        return $action->isAppui(); // direction_appui_id !== null
    }
    
    return $user->hasPermissionTo('view action');
}
```

#### 6.4 Agrégations Globales

**🔴 CRITIQUE** : Toutes les statistiques sont calculées sur l'ensemble des données.

**Exemples** :
```php
// DashboardController.php
'actions_total' => ActionPrioritaire::count(), // ❌ GLOBAL (technique + appui)
'kpis_total' => Kpi::count(), // ❌ GLOBAL
```

**Impact** : Le SG voit les statistiques de TOUTES les actions (techniques + appui).

#### 6.5 Absence de Scopes Eloquent

**❌ MANQUE** :
- `ActionPrioritaire::scopeForAppui()`
- `Kpi::scopeForAppui()`
- `Tache::scopeForAppui()`
- `Alerte::scopeForAppui()`
- `DirectionAppui::scopeActive()`

---

## 📊 RÉSUMÉ DES ÉCARTS À LA RÈGLE MÉTIER

| Règle Métier | État Actuel | Conformité |
|--------------|-------------|------------|
| 1. SG = Autorité Directions d'Appui | ❌ Aucune relation | **NON CONFORME** |
| 2. Périmètre exclusif APPUI | ❌ Accès à tout | **NON CONFORME** |
| 3. Vue consolidée actions APPUI | ❌ Vue globale | **NON CONFORME** |
| 4. Indicateurs transversaux APPUI | ❌ Indicateurs globaux | **NON CONFORME** |
| 5. Validation/arbitrage APPUI | ❌ Endpoints manquants | **NON CONFORME** |
| 6. Suivi risques/retards APPUI | ❌ Vue globale | **NON CONFORME** |

**SCORE DE CONFORMITÉ** : **0/6** (0%)

---

## 📁 FICHIERS IMPACTÉS

### Migrations
- ⏳ `database/migrations/XXXX_XX_XX_XXXXXX_add_type_to_actions_prioritaires.php` → À créer (si besoin)
- ⏳ `database/migrations/XXXX_XX_XX_XXXXXX_add_constraint_action_appui_or_technique.php` → À créer

### Models
- ❌ `app/Models/ActionPrioritaire.php` → À modifier (ajouter relations, scopes, méthodes)
- ❌ `app/Models/DirectionAppui.php` → À modifier (ajouter scopes, méthodes)
- ❌ `app/Models/Kpi.php` → À modifier (ajouter scope forAppui)
- ❌ `app/Models/Tache.php` → À modifier (ajouter scope forAppui)
- ❌ `app/Models/Alerte.php` → À modifier (ajouter scope forAppui)
- ❌ `app/Models/User.php` → À modifier (ajouter isSecretaireGeneral, getAppuiDirections)

### Controllers
- ❌ `app/Http/Controllers/DashboardController.php` → À modifier
- ❌ `app/Http/Controllers/Papa/ActionPrioritaireController.php` → À modifier
- ❌ `app/Http/Controllers/Papa/KpiController.php` → À modifier
- ❌ `app/Http/Controllers/Papa/TacheController.php` → À modifier
- ❌ `app/Http/Controllers/Papa/AlerteController.php` → À modifier
- ❌ `app/Http/Controllers/ExportController.php` → À modifier
- ✅ `app/Http/Controllers/SecretaireGeneral/SecretaireGeneralDashboardController.php` → À créer
- ✅ `app/Http/Controllers/SecretaireGeneral/SecretaireGeneralActionController.php` → À créer
- ✅ `app/Http/Controllers/SecretaireGeneral/SecretaireGeneralValidationController.php` → À créer

### Policies
- ❌ `app/Policies/ActionPrioritairePolicy.php` → À modifier
- ❌ `app/Policies/KpiPolicy.php` → À modifier
- ❌ `app/Policies/TachePolicy.php` → À modifier
- ❌ `app/Policies/AlertePolicy.php` → À modifier

### Routes
- ❌ `routes/web.php` → À modifier (ajouter routes secretaire-general/*)

### Seeders
- ❌ `database/seeders/PermissionsCeeacSeeder.php` → À modifier (restreindre permissions SG)

---

## 🚨 RISQUES DE SÉCURITÉ

### Risque 1 : Accès Non Autorisé aux Directions Techniques
**Niveau** : 🔴 CRITIQUE  
**Description** : Le SG peut voir toutes les actions, y compris celles des Directions Techniques.  
**Impact** : Violation de confidentialité, fuite d'informations stratégiques des départements techniques.  
**Probabilité** : 100% (déjà possible actuellement)

### Risque 2 : Validation/Arbitrage Non Autorisé
**Niveau** : 🔴 CRITIQUE  
**Description** : Le SG peut valider/arbitrer des actions techniques.  
**Impact** : Corruption des données, décisions non autorisées sur le périmètre des Commissaires.  
**Probabilité** : 100% (si endpoints existent sans vérification)

### Risque 3 : Statistiques Faussées
**Niveau** : 🟡 MOYEN  
**Description** : Les tableaux de bord affichent des statistiques globales (technique + appui).  
**Impact** : Prise de décision basée sur des données incorrectes, confusion entre périmètres.  
**Probabilité** : 100% (déjà le cas)

### Risque 4 : Permissions Trop Larges
**Niveau** : 🔴 CRITIQUE  
**Description** : Le SG a des permissions sur TOUT (papa, objectifs, actions, etc.) sans distinction APPUI/TECHNIQUE.  
**Impact** : Accès involontaire aux données techniques via les permissions.  
**Probabilité** : 100% (déjà le cas)

---

## ✅ RECOMMANDATIONS

### Priorité 1 (CRITIQUE - À faire immédiatement)
1. ✅ Ajouter relation `directionAppui()` dans `ActionPrioritaire`
2. ✅ Ajouter scopes `forAppui()` sur tous les modèles concernés
3. ✅ Modifier toutes les policies pour vérifier le scope APPUI pour le SG
4. ✅ Modifier tous les controllers pour appliquer les scopes APPUI
5. ✅ Restreindre les permissions du SG dans `PermissionsCeeacSeeder`

### Priorité 2 (HAUTE - À faire rapidement)
6. ✅ Créer endpoints `/secretaire-general/*` avec middleware `role:secretaire_general`
7. ✅ Créer vues Blade pour le dashboard SG
8. ✅ Implémenter validation/arbitrage scoppé par APPUI
9. ✅ Ajouter audit logging pour validations/arbitrages SG

### Priorité 3 (MOYENNE - À faire après)
10. ✅ Créer tests automatisés (Feature/Pest)
11. ✅ Documenter la règle métier dans README
12. ✅ Ajouter checklist de conformité

---

## 📝 NOTES TECHNIQUES

### Choix d'Architecture Recommandé

**Option A (RECOMMANDÉE)** : Utiliser `direction_appui_id` pour identifier les actions d'appui
- ✅ Simple et direct
- ✅ Pas besoin de migration supplémentaire
- ✅ Scope `forAppui()` = `whereNotNull('direction_appui_id')`
- ✅ Scope `forTechnique()` = `whereNotNull('direction_technique_id')`

**Option B** : Ajouter un champ `type` avec contrainte
- ❌ Plus complexe
- ❌ Nécessite migration
- ✅ Plus explicite

**DÉCISION** : **Option A** avec utilisation de `direction_appui_id` et `direction_technique_id`.

### Méthodes Helper Recommandées

```php
// ActionPrioritaire
public function isAppui(): bool
{
    return $this->direction_appui_id !== null;
}

public function isTechnique(): bool
{
    return $this->direction_technique_id !== null;
}

public function scopeForAppui($query)
{
    return $query->whereNotNull('direction_appui_id');
}

public function scopeForTechnique($query)
{
    return $query->whereNotNull('direction_technique_id');
}
```

---

## 🔄 PROCHAINES ÉTAPES

1. **ÉTAPE B** : Modifier modèles et ajouter scopes
2. **ÉTAPE C** : Implémenter RBAC + Policies + Scopes
3. **ÉTAPE D** : Créer fonctionnalités SG
4. **ÉTAPE E** : Créer tests automatisés
5. **ÉTAPE F** : Checklist et documentation

---

**FIN DU RAPPORT D'AUDIT**

