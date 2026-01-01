# PATCH SECRÉTAIRE GÉNÉRAL / DIRECTIONS D'APPUI
## Corrections Appliquées

**Date** : 2025-01-02  
**Statut** : En cours d'implémentation

---

## ✅ ÉTAPE A : AUDIT (TERMINÉE)

- ✅ Rapport d'audit créé : `docs/AUDIT_SECRETAIRE_GENERAL.md`
- ✅ Écarts identifiés : 0/6 conformité (0%)
- ✅ Risques de sécurité identifiés : 4 critiques

---

## ✅ ÉTAPE B : MODÈLE DE DONNÉES (TERMINÉE)

### Modèles Modifiés

#### `app/Models/ActionPrioritaire.php`
- ✅ Ajout relation `directionAppui()` → `belongsTo(DirectionAppui::class)`
- ✅ Ajout méthode `isAppui()` : vérifie `direction_appui_id !== null`
- ✅ Ajout méthode `isTechnique()` : vérifie `direction_technique_id !== null`
- ✅ Ajout scope `forAppui()` : filtre `whereNotNull('direction_appui_id')`
- ✅ Ajout scope `forTechnique()` : filtre `whereNotNull('direction_technique_id')`

#### `app/Models/DirectionAppui.php`
- ✅ Ajout relation `actionsPrioritaires()` → `hasMany(ActionPrioritaire::class)`
- ✅ Ajout scope `active()` : filtre `where('actif', true)`
- ✅ Ajout méthode `isActive()` : vérifie `actif === true`

#### `app/Models/Kpi.php`
- ✅ Ajout méthode `isAppui()` : vérifie via `actionPrioritaire->isAppui()`
- ✅ Ajout scope `forAppui()` : filtre via `whereHas('actionPrioritaire', ...)`

#### `app/Models/Tache.php`
- ✅ Ajout méthode `isAppui()` : vérifie via `actionPrioritaire->isAppui()`
- ✅ Ajout scope `forAppui()` : filtre via `whereHas('actionPrioritaire', ...)`

#### `app/Models/Alerte.php`
- ✅ Ajout méthode `isAppui()` : vérifie via `actionPrioritaire` ou `tache`
- ✅ Ajout scope `forAppui()` : filtre via `whereHas('actionPrioritaire', ...)` ou `whereHas('tache.actionPrioritaire', ...)`

#### `app/Models/User.php`
- ✅ Ajout méthode `isSecretaireGeneral()` : vérifie rôle `secretaire_general`
- ✅ Ajout méthode `getAppuiDirections()` : retourne toutes les Directions d'Appui actives
- ✅ Ajout méthode `getAppuiDirectionIds()` : retourne les IDs des Directions d'Appui

---

## ✅ ÉTAPE C : RBAC + POLICIES + SCOPES (TERMINÉE)

### Policies Modifiées

#### `app/Policies/ActionPrioritairePolicy.php`
- ✅ `viewAny()` : Ajout vérification SG (scope appliqué dans controller)
- ✅ `view()` : Vérifie que le SG ne voit que les actions d'appui
- ✅ `update()` : Vérifie que le SG ne modifie que les actions d'appui
- ✅ `validate()` : Vérifie que le SG ne valide que les actions d'appui
- ✅ `arbitrate()` : Vérifie que le SG ne peut arbitrer que les actions d'appui

#### `app/Policies/KpiPolicy.php`
- ✅ `view()` : Vérifie que le SG ne voit que les KPIs d'appui

#### `app/Policies/TachePolicy.php`
- ✅ `view()` : Vérifie que le SG ne voit que les tâches d'appui

#### `app/Policies/AlertePolicy.php`
- ✅ `view()` : Vérifie que le SG ne voit que les alertes d'appui

---

## ⏳ ÉTAPE D : FONCTIONNALITÉS SG (À FAIRE)

### Controllers À Modifier

- ⏳ `app/Http/Controllers/DashboardController.php` → Appliquer scope `forAppui()` pour SG
- ⏳ `app/Http/Controllers/Papa/ActionPrioritaireController.php` → Appliquer scope `forAppui()` pour SG
- ⏳ `app/Http/Controllers/Papa/KpiController.php` → Appliquer scope `forAppui()` pour SG
- ⏳ `app/Http/Controllers/Papa/TacheController.php` → Appliquer scope `forAppui()` pour SG
- ⏳ `app/Http/Controllers/Papa/AlerteController.php` → Appliquer scope `forAppui()` pour SG
- ⏳ `app/Http/Controllers/ExportController.php` → Appliquer scope `forAppui()` pour SG

### Controllers À Créer

- ⏳ `app/Http/Controllers/SecretaireGeneral/SecretaireGeneralDashboardController.php`
- ⏳ `app/Http/Controllers/SecretaireGeneral/SecretaireGeneralActionController.php`
- ⏳ `app/Http/Controllers/SecretaireGeneral/SecretaireGeneralValidationController.php`

### Routes À Ajouter

- ⏳ `/secretaire-general/dashboard`
- ⏳ `/secretaire-general/actions`
- ⏳ `/secretaire-general/indicateurs`
- ⏳ `/secretaire-general/risques`
- ⏳ `/secretaire-general/validations`

---

## ⏳ ÉTAPE E : TESTS AUTOMATISÉS (À FAIRE)

### Tests À Créer

1. ⏳ Test : Le SG voit TOUTES les Directions d'Appui
2. ⏳ Test : Le SG ne voit AUCUNE action des Directions Techniques
3. ⏳ Test : Le SG ne peut PAS valider une action technique (403)
4. ⏳ Test : Les KPI SG excluent totalement les Directions Techniques
5. ⏳ Test : Les agrégations globales sont correctement filtrées
6. ⏳ Test : Un Administrateur global conserve l'accès total

---

## ⏳ ÉTAPE F : CHECKLIST & PATCH FINAL (À FAIRE)

- ⏳ Middleware `role:secretaire_general` sur toutes les routes SG
- ⏳ Policies appelées systématiquement via `authorize()`
- ⏳ Scopes utilisés dans toutes les queries
- ⏳ Tests verts obligatoires
- ⏳ Documentation mise à jour
- ⏳ Restreindre permissions SG dans `PermissionsCeeacSeeder`

---

## 📝 NOTES

### Prochaines Actions Immédiates

1. **Modifier les controllers existants** pour appliquer les scopes APPUI
2. **Créer les endpoints `/secretaire-general/*`** avec middleware approprié
3. **Créer les tests automatisés** pour prouver la conformité
4. **Restreindre les permissions du SG** dans `PermissionsCeeacSeeder`
5. **Tester manuellement** avec un utilisateur SG

### Points d'Attention

- ⚠️ Vérifier que tous les controllers appliquent bien les scopes `forAppui()`
- ⚠️ S'assurer que les admins peuvent toujours tout voir
- ⚠️ Tester les cas limites (actions sans direction, etc.)
- ⚠️ Les permissions du SG doivent être restreintes aux actions d'appui uniquement

---

**FIN DU DOCUMENT PATCH**

