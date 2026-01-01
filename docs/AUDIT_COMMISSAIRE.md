# AUDIT COMMISSAIRE / DÉPARTEMENT
## Conformité à la Règle Métier

**Date** : 2025-01-02  
**Auditeur** : Lead Engineer Laravel 11 + Expert RBAC  
**Application** : SUIVI-PAP-CEEAC  
**Version Laravel** : 11.47.0

---

## 📋 RÈGLE MÉTIER À RESPECTER

1. **Les Commissaires assurent le pilotage sectoriel des Départements techniques placés sous leur responsabilité.**
2. **Un Commissaire = Chef d'un Département technique.**
3. **Un Département technique est dirigé par UN seul Commissaire (relation 1–1).**
4. **Dans le système, un Commissaire dispose exclusivement (scope département) :**
   - d'une vue consolidée sur les actions de son département ;
   - d'indicateurs sectoriels liés à son département ;
   - de capacités de validation et d'arbitrage liées à son département ;
   - d'un suivi des risques et retards lié à son département.

---

## 🔍 ÉTAPE A : INVENTAIRE & DIAGNOSTIC

### 1. Schéma Actuel des Tables et Relations

#### 1.1 Table `commissaires`
```sql
- id (PK)
- nom
- prenom
- titre
- commission_id (FK → commissions) ❌ MAUVAISE RELATION
- pays_origine
- date_nomination
- actif
- timestamps
```

**❌ PROBLÈME** : La table `commissaires` est liée à `commissions`, pas à `departements`.  
**❌ PROBLÈME** : Aucune relation avec `users` (pas de `user_id`).  
**❌ PROBLÈME** : Aucune relation avec `departements` (pas de `departement_id`).

#### 1.2 Table `departements`
```sql
- id (PK)
- code (UNIQUE)
- libelle
- description
- actif
- timestamps
```

**❌ PROBLÈME** : Aucune colonne `commissaire_id` ou `commissioner_user_id`.  
**❌ PROBLÈME** : Aucune contrainte UNIQUE assurant "1 département = 1 commissaire".

#### 1.3 Table `users`
```sql
- id (PK)
- name
- email
- ...
- structure_id (FK → structures)
- ...
```

**❌ PROBLÈME** : Aucune colonne `departement_id` ou `commissaire_id`.  
**❌ PROBLÈME** : Aucune relation avec `commissaires` ou `departements`.

#### 1.4 Table `actions_prioritaires`
```sql
- id (PK)
- objectif_id (FK → objectifs)
- direction_technique_id (FK → directions_techniques) ✅ BONNE RELATION
- direction_appui_id (FK → directions_appui)
- ...
```

**✅ BON** : Relation indirecte avec `departements` via `directions_techniques`.  
**❌ PROBLÈME** : Les queries ne sont PAS scoppées par département.

#### 1.5 Table `directions_techniques`
```sql
- id (PK)
- code
- libelle
- departement_id (FK → departements) ✅ BONNE RELATION
- description
- actif
- timestamps
```

**✅ BON** : Relation avec `departements` existe.

### 2. Relations Eloquent Actuelles

#### 2.1 Modèle `Commissaire`
```php
// app/Models/Commissaire.php
public function commission()
{
    return $this->belongsTo(Commission::class);
}
```

**❌ MANQUE** :
- `public function departement()` → `belongsTo(Departement::class)`
- `public function user()` → `belongsTo(User::class)`

#### 2.2 Modèle `Departement`
```php
// app/Models/Departement.php
public function directionsTechniques()
{
    return $this->hasMany(DirectionTechnique::class);
}
```

**❌ MANQUE** :
- `public function commissaire()` → `belongsTo(User::class, 'commissioner_user_id')`
- `public function commissaireModel()` → `hasOne(Commissaire::class, 'departement_id')`

#### 2.3 Modèle `User`
```php
// app/Models/User.php
public function structure()
{
    return $this->belongsTo(Structure::class);
}
```

**❌ MANQUE** :
- `public function departement()` → `hasOne(Departement::class, 'commissioner_user_id')`
- `public function isCommissaire()` → méthode helper

#### 2.4 Modèle `ActionPrioritaire`
```php
// app/Models/ActionPrioritaire.php
public function directionTechnique()
{
    // ❌ MANQUE cette relation
}
```

**❌ MANQUE** :
- `public function directionTechnique()` → `belongsTo(DirectionTechnique::class)`
- `public function departement()` → via `directionTechnique()->departement()`
- Scope `forDepartment($departmentId)`

### 3. Représentation "Commissaire" dans le Système

**ACTUELLEMENT** :
- ✅ Rôle Spatie `'commissaire'` existe (voir `RolesCeeacSeeder`)
- ❌ Mais aucun lien entre `User` et `Commissaire` (table séparée)
- ❌ Aucun lien entre `User` et `Departement`
- ❌ La table `commissaires` est une entité référentielle, pas un rôle fonctionnel

**RECOMMANDATION** :
- Option A (RECOMMANDÉE) : `departements.commissioner_user_id UNIQUE` → FK vers `users.id`
- Option B : `users.departement_id` + `users.is_commissaire` boolean
- Option C : Table pivot `commissaire_departement` avec contrainte UNIQUE

### 4. Contrainte 1–1 Actuelle

**❌ AUCUNE CONTRAINTE** :
- Pas de colonne `commissioner_user_id` dans `departements`
- Pas de colonne `departement_id` dans `commissaires`
- Pas de contrainte UNIQUE assurant "1 département = 1 commissaire"

**MIGRATION NÉCESSAIRE** :
```php
Schema::table('departements', function (Blueprint $table) {
    $table->foreignId('commissioner_user_id')
        ->nullable()
        ->unique()
        ->constrained('users')
        ->onDelete('set null');
});
```

### 5. Écrans/Endpoints Utilisés par un Commissaire

#### 5.1 Endpoints Existants (NON SCOPPÉS)

| Endpoint | Controller | Méthode | Scope Département ? |
|----------|------------|---------|---------------------|
| `/actions-prioritaires` | `ActionPrioritaireController::index()` | ❌ NON | Récupère TOUTES les actions |
| `/actions-prioritaires/{id}` | `ActionPrioritaireController::show()` | ❌ NON | Aucune vérification département |
| `/dashboard` | `DashboardController::index()` | ❌ NON | Statistiques GLOBALES |
| `/kpi` | `KpiController::index()` | ❌ NON | Tous les KPIs |
| `/alertes` | `AlerteController::index()` | ❌ NON | Toutes les alertes |
| `/taches` | `TacheController::index()` | ❌ NON | Toutes les tâches |

#### 5.2 Endpoints Manquants (À CRÉER)

| Endpoint | Description | Statut |
|----------|-------------|--------|
| `/commissaire/actions` | Vue consolidée actions département | ❌ MANQUANT |
| `/commissaire/indicateurs` | Indicateurs sectoriels | ❌ MANQUANT |
| `/commissaire/risques` | Suivi risques/retards | ❌ MANQUANT |
| `/commissaire/validations` | Validation/arbitrage | ❌ MANQUANT |

### 6. Failles de Sécurité Identifiées

#### 6.1 Data Leakage Inter-Départements

**🔴 CRITIQUE** : `ActionPrioritaireController::index()`
```php
// Ligne 16-21
$query = ActionPrioritaire::with([...]);
// ❌ Aucun filtre par département
// Un commissaire D1 peut voir TOUTES les actions de D2, D3, etc.
```

**🔴 CRITIQUE** : `DashboardController::index()`
```php
// Lignes 26-40
'actions_total' => ActionPrioritaire::count(), // ❌ GLOBAL
'actions_en_cours' => ActionPrioritaire::whereIn(...)->count(), // ❌ GLOBAL
'kpis_total' => Kpi::count(), // ❌ GLOBAL
```

**🔴 CRITIQUE** : `ActionPrioritairePolicy::view()`
```php
// Ligne 29-38
public function view(User $user, ActionPrioritaire $actionPrioritaire): bool
{
    if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
        return true; // ✅ OK pour admin
    }
    // ❌ Aucune vérification : $actionPrioritaire->departement_id === $user->departement_id
    return $user->hasPermissionTo('view action');
}
```

#### 6.2 Queries Globales Sans Filtre

**Fichiers Impactés** :
- `app/Http/Controllers/Papa/ActionPrioritaireController.php` : Lignes 16, 85-93
- `app/Http/Controllers/DashboardController.php` : Lignes 26-84
- `app/Http/Controllers/Papa/KpiController.php` : Lignes 17, 79-82
- `app/Http/Controllers/Papa/TacheController.php` : Lignes 17, 71-76
- `app/Http/Controllers/Papa/AlerteController.php` : Lignes 63, 73
- `app/Http/Controllers/ExportController.php` : Lignes 53, 57, 85, 88

**Exemple Typique** :
```php
// ❌ MAUVAIS
$actions = ActionPrioritaire::all();

// ✅ BON (à implémenter)
$actions = ActionPrioritaire::forDepartment($user->departement_id)->get();
```

#### 6.3 Policies Absentes ou Incomplètes

**Policies Existantes** :
- ✅ `ActionPrioritairePolicy` existe
- ✅ `KpiPolicy` existe (à vérifier)
- ✅ `TachePolicy` existe (à vérifier)

**❌ PROBLÈME** : Aucune policy ne vérifie le scope département.

**Exemple de Correction Nécessaire** :
```php
// ❌ ACTUEL
public function view(User $user, ActionPrioritaire $action): bool
{
    return $user->hasPermissionTo('view action');
}

// ✅ CORRIGÉ
public function view(User $user, ActionPrioritaire $action): bool
{
    if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
        return true;
    }
    
    if ($user->hasRole('commissaire')) {
        $userDepartmentId = $user->departement?->id;
        $actionDepartmentId = $action->directionTechnique?->departement_id;
        return $userDepartmentId === $actionDepartmentId;
    }
    
    return $user->hasPermissionTo('view action');
}
```

#### 6.4 Agrégations Globales

**🔴 CRITIQUE** : Toutes les statistiques sont calculées sur l'ensemble des données.

**Exemples** :
```php
// DashboardController.php
'actions_total' => ActionPrioritaire::count(), // ❌ GLOBAL
'kpis_sous_seuil' => Kpi::where(...)->count(), // ❌ GLOBAL
```

**Impact** : Un commissaire voit les statistiques de TOUS les départements.

---

## 📊 RÉSUMÉ DES ÉCARTS À LA RÈGLE MÉTIER

| Règle Métier | État Actuel | Conformité |
|--------------|-------------|------------|
| 1. Commissaire = Chef Département | ❌ Aucune relation | **NON CONFORME** |
| 2. Relation 1–1 Commissaire ↔ Département | ❌ Aucune contrainte | **NON CONFORME** |
| 3. Vue consolidée actions département | ❌ Vue globale | **NON CONFORME** |
| 4. Indicateurs sectoriels | ❌ Indicateurs globaux | **NON CONFORME** |
| 5. Validation/arbitrage scoppé | ❌ Endpoints manquants | **NON CONFORME** |
| 6. Suivi risques/retards scoppé | ❌ Vue globale | **NON CONFORME** |

**SCORE DE CONFORMITÉ** : **0/6** (0%)

---

## 📁 FICHIERS IMPACTÉS

### Migrations
- ❌ `database/migrations/2025_12_30_065805_create_departements_table.php` → À modifier
- ✅ `database/migrations/XXXX_XX_XX_XXXXXX_add_commissioner_to_departements.php` → À créer

### Models
- ❌ `app/Models/Departement.php` → À modifier
- ❌ `app/Models/User.php` → À modifier
- ❌ `app/Models/Commissaire.php` → À modifier (ou supprimer si on utilise User)
- ❌ `app/Models/ActionPrioritaire.php` → À modifier (ajouter scopes)
- ❌ `app/Models/Kpi.php` → À modifier (ajouter scopes)
- ❌ `app/Models/Tache.php` → À modifier (ajouter scopes)
- ❌ `app/Models/Alerte.php` → À modifier (ajouter scopes)

### Controllers
- ❌ `app/Http/Controllers/Papa/ActionPrioritaireController.php` → À modifier
- ❌ `app/Http/Controllers/DashboardController.php` → À modifier
- ❌ `app/Http/Controllers/Papa/KpiController.php` → À modifier
- ❌ `app/Http/Controllers/Papa/TacheController.php` → À modifier
- ❌ `app/Http/Controllers/Papa/AlerteController.php` → À modifier
- ❌ `app/Http/Controllers/ExportController.php` → À modifier
- ✅ `app/Http/Controllers/Commissaire/CommissaireDashboardController.php` → À créer
- ✅ `app/Http/Controllers/Commissaire/CommissaireActionController.php` → À créer
- ✅ `app/Http/Controllers/Commissaire/CommissaireValidationController.php` → À créer

### Policies
- ❌ `app/Policies/ActionPrioritairePolicy.php` → À modifier
- ❌ `app/Policies/KpiPolicy.php` → À vérifier/modifier
- ❌ `app/Policies/TachePolicy.php` → À vérifier/modifier
- ❌ `app/Policies/AlertePolicy.php` → À vérifier/modifier

### Routes
- ❌ `routes/web.php` → À modifier (ajouter routes commissaire)

### Services/Repositories (si existants)
- ❌ Tous les services qui font des queries → À vérifier

---

## 🚨 RISQUES DE SÉCURITÉ

### Risque 1 : Data Leakage Inter-Départements
**Niveau** : 🔴 CRITIQUE  
**Description** : Un commissaire peut voir toutes les actions, KPIs, tâches de tous les départements.  
**Impact** : Violation de confidentialité, fuite d'informations stratégiques.  
**Probabilité** : 100% (déjà possible actuellement)

### Risque 2 : Validation/Arbitrage Non Autorisé
**Niveau** : 🔴 CRITIQUE  
**Description** : Un commissaire peut valider/arbitrer des actions d'autres départements.  
**Impact** : Corruption des données, décisions non autorisées.  
**Probabilité** : 100% (si endpoints existent sans vérification)

### Risque 3 : Statistiques Faussées
**Niveau** : 🟡 MOYEN  
**Description** : Les tableaux de bord affichent des statistiques globales, pas par département.  
**Impact** : Prise de décision basée sur des données incorrectes.  
**Probabilité** : 100% (déjà le cas)

### Risque 4 : Absence de Traçabilité
**Niveau** : 🟡 MOYEN  
**Description** : Pas d'audit log spécifique pour les actions de validation/arbitrage.  
**Impact** : Impossibilité de tracer qui a validé quoi et quand.  
**Probabilité** : 100% (si endpoints manquants)

---

## ✅ RECOMMANDATIONS

### Priorité 1 (CRITIQUE - À faire immédiatement)
1. ✅ Créer migration `add_commissioner_user_id_to_departements`
2. ✅ Modifier modèles pour ajouter relations 1–1
3. ✅ Ajouter scopes `forDepartment()` sur tous les modèles concernés
4. ✅ Modifier toutes les policies pour vérifier le scope département
5. ✅ Modifier tous les controllers pour appliquer les scopes

### Priorité 2 (HAUTE - À faire rapidement)
6. ✅ Créer endpoints `/commissaire/*` avec middleware `role:commissaire`
7. ✅ Créer vues Blade pour le dashboard commissaire
8. ✅ Implémenter validation/arbitrage scoppé par département
9. ✅ Ajouter audit logging pour validations/arbitrages

### Priorité 3 (MOYENNE - À faire après)
10. ✅ Créer tests automatisés (Feature/Pest)
11. ✅ Documenter la règle métier dans README
12. ✅ Ajouter checklist de conformité

---

## 📝 NOTES TECHNIQUES

### Choix d'Architecture Recommandé

**Option A (RECOMMANDÉE)** : `departements.commissioner_user_id UNIQUE`
- ✅ Simple et direct
- ✅ Contrainte DB garantit 1–1
- ✅ Pas besoin de table `commissaires` séparée (ou la garder comme référentiel)
- ✅ User peut être commissaire via `$user->departement`

**Option B** : Table pivot `commissaire_departement`
- ❌ Plus complexe
- ❌ Nécessite contrainte UNIQUE supplémentaire
- ✅ Permet historique (si besoin)

**Option C** : `users.departement_id` + `users.is_commissaire`
- ❌ Moins flexible
- ❌ Un user ne peut être commissaire que d'un seul département (OK pour la règle)

**DÉCISION** : **Option A** avec `departements.commissioner_user_id UNIQUE`.

---

## 🔄 PROCHAINES ÉTAPES

1. **ÉTAPE B** : Créer migration et modifier modèles
2. **ÉTAPE C** : Implémenter RBAC + Policies + Scopes
3. **ÉTAPE D** : Créer fonctionnalités Commissaire
4. **ÉTAPE E** : Créer tests automatisés
5. **ÉTAPE F** : Checklist et documentation

---

**FIN DU RAPPORT D'AUDIT**

