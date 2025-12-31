# Plan de Corrections Détaillé - SUIVI-PAPA CEEAC

## État d'Avancement Global

- ✅ **Audit complet**: Terminé
- 🔄 **Phase 1 - Sécurité**: En cours (30%)
- ⏳ **Phase 2 - Services**: À faire
- ⏳ **Phase 3 - Import Excel**: À faire
- ⏳ **Phase 4 - Vues**: À faire
- ⏳ **Phase 5 - Tests**: À faire

---

## PHASE 1: SÉCURITÉ & ARCHITECTURE (30% complété)

### ✅ Fait
1. Créé `PermissionsCeeacSeeder` avec toutes les permissions
2. Corrigé `PapaPolicy` et `ObjectifPolicy`
3. Enregistré Policies dans `AppServiceProvider`
4. Corrigé `StoreObjectifRequest` et utilisé dans Controller

### ⏳ À Faire (URGENT)

#### 1.1. Corriger toutes les Policies restantes
- [ ] `ActionPrioritairePolicy`
- [ ] `TachePolicy`
- [ ] `KpiPolicy`
- [ ] `AlertePolicy`
- [ ] `AvancementPolicy`

**Template à utiliser**:
```php
public function viewAny(User $user): bool
{
    return $user->can('viewAny [module]');
}
```

#### 1.2. Corriger tous les FormRequests
- [ ] `StorePapaRequest` / `UpdatePapaRequest`
- [ ] `StoreActionPrioritaireRequest` / `UpdateActionPrioritaireRequest`
- [ ] `StoreTacheRequest` / `UpdateTacheRequest`
- [ ] `StoreKpiRequest` / `UpdateKpiRequest`
- [ ] `StoreAlerteRequest` / `UpdateAlerteRequest`
- [ ] `StoreAvancementRequest` / `UpdateAvancementRequest`
- [ ] Tous les FormRequests Référentiels

**Template à utiliser**:
```php
public function authorize(): bool
{
    return $this->user()->can('create', Model::class);
}

public function rules(): array
{
    return [
        // Règles de validation complètes
    ];
}
```

#### 1.3. Utiliser FormRequests dans tous les Controllers
- [ ] `PapaController::store()` et `update()`
- [ ] `ActionPrioritaireController::store()` et `update()`
- [ ] `TacheController::store()` et `update()`
- [ ] `KpiController::store()` et `update()`
- [ ] `AlerteController::store()` et `update()`
- [ ] `AvancementController::store()` et `update()`

---

## PHASE 2: SERVICES & LOGIQUE MÉTIER (0% complété)

### ⏳ À Créer

#### 2.1. Services Métier
- [ ] `app/Services/PapaImportService.php`
  - Méthode: `importFromExcel($file, $versionId)`
  - Validation des lignes
  - Mapping colonnes → entités
  - Gestion des erreurs et rejets
  - Rapport d'import

- [ ] `app/Services/AlerteService.php`
  - Méthode: `checkRetards()`
  - Méthode: `checkKpiSousSeuil()`
  - Méthode: `escalade($alerte)`
  - Méthode: `generateAlertes()`

- [ ] `app/Services/KpiService.php`
  - Méthode: `calculerKpi($kpiId)`
  - Méthode: `agregerParDirection()`
  - Méthode: `agregerParDepartement()`

- [ ] `app/Services/AvancementService.php`
  - Méthode: `calculerAvancementHierarchique($objectifId)`
  - Méthode: `propagerAvancement($tacheId)`

#### 2.2. Jobs
- [ ] `app/Jobs/ImportPapaJob.php`
  - Queue: `imports`
  - Retry: 3
  - Timeout: 300s

- [ ] `app/Jobs/GenerateAlertesJob.php`
  - Queue: `default`
  - Schedule: quotidien

- [ ] `app/Jobs/ExportPapaJob.php`
  - Queue: `exports`
  - Retry: 2

#### 2.3. Events/Listeners
- [ ] `app/Events/ObjectifCreated.php`
- [ ] `app/Events/ActionUpdated.php`
- [ ] `app/Events/AvancementUpdated.php`
- [ ] `app/Listeners/RecalculerKpi.php`
- [ ] `app/Listeners/GenererAlertes.php`
- [ ] `app/Listeners/LoggerActivite.php`

---

## PHASE 3: IMPORT EXCEL (0% complété)

### ⏳ À Implémenter

#### 3.1. PapaImport
- [ ] Mapping complet des colonnes Excel
- [ ] Validation des données (codes, dates, statuts)
- [ ] Gestion des erreurs ligne par ligne
- [ ] Rapport d'import détaillé

#### 3.2. ImportController
- [ ] Validation du fichier (type, taille)
- [ ] Appel du Service ou Job
- [ ] Affichage du rapport d'import
- [ ] Gestion des erreurs

#### 3.3. Vues Import
- [ ] Formulaire d'upload
- [ ] Affichage du rapport d'import
- [ ] Liste des imports historiques

---

## PHASE 4: VUES & UI (10% complété)

### ✅ Fait
- [x] Vue `objectifs/create.blade.php` complète

### ⏳ À Créer

#### 4.1. Vues Edit
- [ ] `papa/objectifs/edit.blade.php`
- [ ] `papa/actions-prioritaires/edit.blade.php`
- [ ] `papa/taches/edit.blade.php`
- [ ] `papa/kpi/edit.blade.php`
- [ ] `papa/alertes/edit.blade.php`
- [ ] `papa/avancements/edit.blade.php`

#### 4.2. Vues Show
- [ ] `papa/objectifs/show.blade.php`
- [ ] `papa/actions-prioritaires/show.blade.php`
- [ ] `papa/taches/show.blade.php`
- [ ] `papa/kpi/show.blade.php`
- [ ] `papa/alertes/show.blade.php`
- [ ] `papa/avancements/show.blade.php`

#### 4.3. Améliorations UI
- [ ] Breadcrumbs component
- [ ] Filtres avancés dans les listes
- [ ] Modals pour actions rapides
- [ ] Composants Blade réutilisables (cards, tables, badges)

---

## PHASE 5: TESTS (0% complété)

### ⏳ À Créer

#### 5.1. Tests Feature
- [ ] `tests/Feature/Papa/ObjectifTest.php`
  - test_can_create_objectif
  - test_cannot_create_without_permission
  - test_validation_rules

- [ ] `tests/Feature/Papa/ImportTest.php`
  - test_can_import_excel
  - test_rejects_invalid_file
  - test_import_report

- [ ] `tests/Feature/PermissionsTest.php`
  - test_roles_have_correct_permissions
  - test_policies_work_correctly

#### 5.2. Tests Unit
- [ ] `tests/Unit/Services/AlerteServiceTest.php`
- [ ] `tests/Unit/Services/KpiServiceTest.php`
- [ ] `tests/Unit/Services/AvancementServiceTest.php`

---

## ORDRE D'EXÉCUTION RECOMMANDÉ

1. **JOUR 1**: Finir Phase 1 (Policies + FormRequests)
2. **JOUR 2**: Phase 2 (Services métier)
3. **JOUR 3**: Phase 3 (Import Excel)
4. **JOUR 4**: Phase 4 (Vues manquantes)
5. **JOUR 5**: Phase 5 (Tests)

---

## NOTES IMPORTANTES

- ⚠️ Ne pas casser l'existant: tester après chaque modification
- ⚠️ Commits petits et logiques
- ⚠️ Respecter les conventions Laravel 11
- ⚠️ Documenter les décisions importantes


