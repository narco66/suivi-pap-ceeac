# Schéma d'Ordre de Seeding - SUIVI-PAPA CEEAC

## Ordre d'Exécution des Seeders (Dépendances)

```
1. PermissionsCeeacSeeder
   └─> Crée les permissions et les assigne aux rôles

2. ReferentielsSeeder
   ├─> Départements
   ├─> Directions Techniques
   ├─> Directions d'Appui
   ├─> Commissions
   └─> Commissaires

3. UsersSeeder
   └─> Utilisateurs avec rôles (dépend de ReferentielsSeeder)

4. PapaSeeder (Scénarios A, B, C)
   ├─> PAPA 2024 (archivé) - Scénario C
   │   ├─> PapaVersion 2024 v1 (verrouillée)
   │   │   ├─> Objectifs (10-20)
   │   │   │   ├─> Actions Prioritaires (60-120)
   │   │   │   │   ├─> Tâches (300-800)
   │   │   │   │   │   ├─> Sous-tâches (0-3 par tâche)
   │   │   │   │   │   └─> Avancements (12 par tâche)
   │   │   │   │   └─> KPI (3 par action)
   │   │   │   └─> Alertes (liées aux actions/tâches)
   │   │   └─> PapaVersion 2024 v2 (archivée)
   │
   ├─> PAPA 2025 v1 (verrouillée) - Scénario A
   │   ├─> PapaVersion 2025 v1 (verrouillée)
   │   │   ├─> Objectifs (15-20)
   │   │   │   ├─> Actions Prioritaires (80-120)
   │   │   │   │   ├─> Tâches (400-800)
   │   │   │   │   │   ├─> Sous-tâches (0-3 par tâche)
   │   │   │   │   │   └─> Avancements (12 par tâche)
   │   │   │   │   └─> KPI (3-5 par action)
   │   │   │   └─> Alertes (liées aux actions/tâches)
   │
   └─> PAPA 2025 v2 (brouillon) - Scénario B
       └─> PapaVersion 2025 v2 (brouillon)
           ├─> Objectifs (8-12)
           │   ├─> Actions Prioritaires (40-60)
           │   │   ├─> Tâches (200-400)
           │   │   │   ├─> Sous-tâches (0-3 par tâche)
           │   │   │   └─> Avancements (6 par tâche)
           │   │   └─> KPI (2-3 par action)
           │   └─> Alertes (moins nombreuses)

5. KpiValuesSeeder
   └─> Génère valeurs historiques pour KPI (mensuelles/hebdo)

6. AlertesSeeder
   └─> Génère alertes automatiques (retards, KPI sous seuil, blocages)

7. AnomaliesSeeder
   └─> Génère anomalies détectées (dates incohérentes, dépendances)

8. JournauxSeeder
   └─> Génère 5000-30000 lignes d'audit (historique complet)

9. AttachmentsSeeder (optionnel)
   └─> Génère pièces jointes factices dans storage
```

## Contraintes de Clés Étrangères

- `papa_versions.papa_id` → `papas.id`
- `objectifs.papa_version_id` → `papa_versions.id`
- `actions_prioritaires.objectif_id` → `objectifs.id`
- `actions_prioritaires.direction_technique_id` → `directions_techniques.id`
- `actions_prioritaires.direction_appui_id` → `directions_appui.id`
- `taches.action_prioritaire_id` → `actions_prioritaires.id`
- `taches.tache_parent_id` → `taches.id`
- `taches.responsable_id` → `users.id`
- `kpis.action_prioritaire_id` → `actions_prioritaires.id`
- `avancements.tache_id` → `taches.id`
- `avancements.soumis_par_id` → `users.id`
- `avancements.valide_par_id` → `users.id`
- `alertes.tache_id` → `taches.id`
- `alertes.action_prioritaire_id` → `actions_prioritaires.id`
- `alertes.cree_par_id` → `users.id`
- `alertes.assignee_a_id` → `users.id`
- `journaux.utilisateur_id` → `users.id`

## Répartition des Statuts (Scénario A - PAPA 2025 v1)

- **25%** achevée (termine)
- **45%** en_cours
- **15%** planifiée (planifie)
- **10%** en_attente (en_attente)
- **5%** annulée (annule)

## Répartition des Criticités

- **Normal**: 40%
- **Vigilance**: 35%
- **Critique**: 25%

## Dates de Référence

- **PAPA 2024**: 2024-01-01 → 2024-12-31
- **PAPA 2025**: 2025-01-01 → 2025-12-31
- **Avancements**: 6 mois d'historique (now - 6 months → now)



