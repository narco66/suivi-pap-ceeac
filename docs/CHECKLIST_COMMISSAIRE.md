# CHECKLIST DE CONFORMITÉ COMMISSAIRE / DÉPARTEMENT
## Validation Finale

**Date** : 2025-01-02  
**Statut** : En attente de validation

---

## ✅ MODÈLES & RELATIONS

- [x] Migration `add_commissioner_user_id_to_departements_table` créée
- [x] Relation 1-1 `Departement` ↔ `User` (commissaire) implémentée
- [x] Contrainte UNIQUE sur `commissioner_user_id` dans `departements`
- [x] Méthode `User::isCommissaire()` implémentée
- [x] Méthode `User::getDepartmentId()` implémentée
- [x] Scope `forDepartment()` sur `ActionPrioritaire`
- [x] Scope `forDepartment()` sur `Kpi`
- [x] Scope `forDepartment()` sur `Tache`
- [x] Scope `forDepartment()` sur `Alerte`
- [x] Scope `forDepartment()` sur `Objectif`

---

## ✅ POLICIES

- [x] `ActionPrioritairePolicy::view()` vérifie le département
- [x] `ActionPrioritairePolicy::update()` vérifie le département
- [x] `ActionPrioritairePolicy::validate()` vérifie le département
- [x] `KpiPolicy::view()` vérifie le département
- [x] `TachePolicy::view()` vérifie le département
- [x] `AlertePolicy::view()` vérifie le département

---

## ✅ CONTROLLERS

- [x] `DashboardController::index()` : Scope département appliqué
- [x] `ObjectifController::index()` : Scope département appliqué
- [x] `ObjectifController::show()` : Vérification département
- [x] `ActionPrioritaireController::index()` : Scope département appliqué
- [x] `ActionPrioritaireController::show()` : `authorize()` ajouté
- [x] `TacheController::index()` : Scope département appliqué
- [x] `TacheController::show()` : `authorize()` ajouté
- [x] `KpiController::index()` : Scope département appliqué
- [x] `KpiController::show()` : `authorize()` ajouté
- [x] `AlerteController::index()` : Scope département appliqué
- [x] `AlerteController::show()` : `authorize()` ajouté
- [x] `AlerteController::create()` : Tâches/actions scoppées
- [x] `ExportController::export()` : Scope département appliqué

---

## ✅ STATISTIQUES & AGRÉGATIONS

- [x] Statistiques `DashboardController` scoppées par département
- [x] Statistiques `ObjectifController` scoppées par département
- [x] Statistiques `ActionPrioritaireController` scoppées par département
- [x] Statistiques `TacheController` scoppées par département
- [x] Statistiques `KpiController` scoppées par département
- [x] Statistiques `AlerteController` scoppées par département

---

## ✅ EXCLUSIONS

- [x] Les commissaires ne voient PAS les Directions d'Appui (scope `forDepartment()` exclut `direction_appui_id`)
- [x] Les commissaires ne voient PAS les autres départements (filtre strict sur `departement_id`)
- [x] Les admins voient toujours tout (pas de scope pour `admin` et `admin_dsi`)

---

## ⏳ TESTS AUTOMATISÉS (À CRÉER)

- [ ] Test : Commissaire D1 ne voit pas les actions de D2
- [ ] Test : Commissaire D1 ne voit pas les Directions d'Appui
- [ ] Test : Commissaire D1 ne peut pas valider une action D2 (403)
- [ ] Test : Les KPI calculés excluent les autres départements
- [ ] Test : Les exports sont scoppés par département
- [ ] Test : Les statistiques sont scoppées par département
- [ ] Test : Un admin peut voir tout

---

## ⏳ MIGRATION & CONFIGURATION

- [ ] Migration `add_commissioner_user_id_to_departements_table` exécutée
- [ ] Au moins un utilisateur configuré comme commissaire d'un département
- [ ] Test manuel avec un utilisateur commissaire effectué

---

## 📝 NOTES

### Commandes à Exécuter

```bash
# 1. Exécuter la migration
php artisan migrate

# 2. Assigner un commissaire à un département (via tinker ou seeder)
php artisan tinker
>>> $user = User::find(1);
>>> $user->assignRole('commissaire');
>>> $departement = Departement::find(1);
>>> $departement->update(['commissioner_user_id' => $user->id]);
```

### Vérifications Manuelles

1. Se connecter avec un utilisateur commissaire
2. Vérifier que le dashboard ne montre que les données du département
3. Vérifier que `/actions-prioritaires` ne montre que les actions du département
4. Vérifier que `/objectifs` ne montre que les objectifs avec actions du département
5. Vérifier que `/taches` ne montre que les tâches du département
6. Vérifier que `/kpi` ne montre que les KPIs du département
7. Vérifier que `/alertes` ne montre que les alertes du département
8. Vérifier qu'un export ne contient que les données du département
9. Vérifier qu'un commissaire D1 ne peut pas accéder à une action D2 (403)

---

**FIN DE LA CHECKLIST**

