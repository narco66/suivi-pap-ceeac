# PATCH COMMISSAIRE / DÉPARTEMENT
## Corrections Appliquées

**Date** : 2025-01-02  
**Statut** : En cours d'implémentation

---

## ✅ ÉTAPE B : MODÈLE & CONTRAINTES (TERMINÉE)

### 1. Migration Créée
- ✅ `database/migrations/2026_01_01_130312_add_commissioner_user_id_to_departements_table.php`
  - Ajoute `commissioner_user_id` avec contrainte UNIQUE
  - Garantit la relation 1-1 : 1 département = 1 commissaire

### 2. Modèles Modifiés

#### `app/Models/Departement.php`
- ✅ Ajout relation `commissaire()` → `belongsTo(User::class, 'commissioner_user_id')`
- ✅ Ajout méthode `hasCommissaire()`
- ✅ Ajout scopes `withCommissaire()` et `withoutCommissaire()`

#### `app/Models/User.php`
- ✅ Ajout relation `departement()` → `hasOne(Departement::class, 'commissioner_user_id')`
- ✅ Ajout méthode `isCommissaire()` : vérifie rôle + département
- ✅ Ajout méthode `getDepartmentId()` : retourne l'ID du département

#### `app/Models/ActionPrioritaire.php`
- ✅ Ajout relation `directionTechnique()` → `belongsTo(DirectionTechnique::class)`
- ✅ Ajout relation `departement()` → `hasOneThrough(...)`
- ✅ Ajout scope `forDepartment($departmentId)`
- ✅ Ajout méthode `getDepartmentId()`

#### `app/Models/Kpi.php`
- ✅ Ajout relation `departement()` (indirecte via actionPrioritaire)
- ✅ Ajout scope `forDepartment($departmentId)`
- ✅ Ajout méthode `getDepartmentId()`

#### `app/Models/Tache.php`
- ✅ Ajout relation `departement()` (indirecte via actionPrioritaire)
- ✅ Ajout scope `forDepartment($departmentId)`
- ✅ Ajout méthode `getDepartmentId()`

#### `app/Models/Alerte.php`
- ✅ Ajout relation `departement()` (indirecte via actionPrioritaire ou tache)
- ✅ Ajout scope `forDepartment($departmentId)`
- ✅ Ajout méthode `getDepartmentId()`

---

## ✅ ÉTAPE C : RBAC + POLICIES + SCOPES (EN COURS)

### 1. Policies Modifiées

#### `app/Policies/ActionPrioritairePolicy.php`
- ✅ `view()` : Vérifie que le commissaire ne voit que les actions de son département
- ✅ `update()` : Vérifie que le commissaire ne modifie que les actions de son département
- ✅ `delete()` : Les commissaires ne peuvent pas supprimer (seuls les admins)
- ✅ `validate()` : Nouvelle méthode pour validation par commissaire
- ✅ `arbitrate()` : Nouvelle méthode pour arbitrage par commissaire

#### `app/Policies/KpiPolicy.php`
- ✅ `view()` : Vérifie que le commissaire ne voit que les KPIs de son département

#### `app/Policies/TachePolicy.php`
- ✅ `view()` : Vérifie que le commissaire ne voit que les tâches de son département

#### `app/Policies/AlertePolicy.php`
- ✅ `view()` : Vérifie que le commissaire ne voit que les alertes de son département

### 2. Controllers Modifiés

#### `app/Http/Controllers/Papa/ActionPrioritaireController.php`
- ✅ `index()` : Applique le scope `forDepartment()` pour les commissaires
- ✅ `show()` : Ajoute `authorize('view', $action)`
- ✅ Statistiques : Scoppées par département pour les commissaires

### 3. Controllers À Modifier (EN ATTENTE)

- ⏳ `app/Http/Controllers/DashboardController.php`
- ⏳ `app/Http/Controllers/Papa/KpiController.php`
- ⏳ `app/Http/Controllers/Papa/TacheController.php`
- ⏳ `app/Http/Controllers/Papa/AlerteController.php`
- ⏳ `app/Http/Controllers/ExportController.php`

---

## ⏳ ÉTAPE D : FONCTIONNALITÉS COMMISSAIRE (À FAIRE)

### Endpoints À Créer

1. ⏳ `/commissaire/dashboard` - Dashboard sectoriel
2. ⏳ `/commissaire/actions` - Vue consolidée actions
3. ⏳ `/commissaire/indicateurs` - Indicateurs sectoriels
4. ⏳ `/commissaire/risques` - Suivi risques/retards
5. ⏳ `/commissaire/validations` - Validation/arbitrage

### Controllers À Créer

- ⏳ `app/Http/Controllers/Commissaire/CommissaireDashboardController.php`
- ⏳ `app/Http/Controllers/Commissaire/CommissaireActionController.php`
- ⏳ `app/Http/Controllers/Commissaire/CommissaireValidationController.php`

---

## ⏳ ÉTAPE E : TESTS AUTOMATISÉS (À FAIRE)

### Tests À Créer

1. ⏳ Test : Un commissaire D1 ne voit pas les actions de D2
2. ⏳ Test : Un commissaire D1 ne peut pas valider une action D2
3. ⏳ Test : Les agrégations KPI sont scoppées par département
4. ⏳ Test : La contrainte DB empêche 2 commissaires sur 1 département
5. ⏳ Test : Un admin peut voir tout

---

## ⏳ ÉTAPE F : CHECKLIST + PATCH FINAL (À FAIRE)

- ⏳ Checklist de conformité
- ⏳ Mise à jour README
- ⏳ Middleware `role:commissaire` sur routes
- ⏳ Tests OK

---

## 📝 NOTES

### Prochaines Actions Immédiates

1. **Modifier les controllers restants** pour appliquer les scopes département
2. **Créer les endpoints commissaire** avec middleware approprié
3. **Créer les tests automatisés** pour prouver la conformité
4. **Exécuter la migration** : `php artisan migrate`
5. **Tester manuellement** avec un utilisateur commissaire

### Points d'Attention

- ⚠️ Les relations `departement()` dans Kpi, Tache, Alerte utilisent des relations indirectes
- ⚠️ Vérifier que tous les controllers appliquent bien les scopes
- ⚠️ S'assurer que les admins peuvent toujours tout voir
- ⚠️ Tester les cas limites (actions sans département, etc.)

---

**FIN DU DOCUMENT PATCH**

