# Mapping Gantt - Réutilisation des Tables Existantes

## Décision : Réutiliser les Tables Existantes

Après analyse du codebase, nous avons décidé de **réutiliser les tables existantes** plutôt que de créer de nouvelles tables `gantt_*`. Cette approche est plus cohérente avec l'architecture existante et évite la duplication de données.

## Mapping des Tables

### 1. Tâches (Gantt Tasks)
**Table existante**: `taches`
**Réutilisation**: ✅ OUI

**Champs existants utilisables**:
- `id` → ID unique de la tâche
- `action_prioritaire_id` → Lien vers l'action prioritaire (projet/phase)
- `tache_parent_id` → Hiérarchie (phases → sous-tâches)
- `code`, `libelle`, `description` → Informations de la tâche
- `date_debut_prevue`, `date_fin_prevue` → Dates prévues (start/end)
- `date_debut_reelle`, `date_fin_reelle` → Dates réelles (actual_start/actual_end)
- `pourcentage_avancement` → Progress (0-100)
- `responsable_id` → Responsible user
- `est_jalon` → Type milestone
- `statut` → Status
- `priorite`, `criticite` → Priorité et criticité

**Champs Gantt déjà présents**:
- `baseline_start`, `baseline_end` → Baseline pour comparaison
- `gantt_color` → Couleur personnalisée
- `gantt_sort_order` → Ordre d'affichage
- `is_critical` → Chemin critique
- `gantt_notes` → Notes Gantt

**Aucune migration supplémentaire nécessaire** ✅

### 2. Dépendances Gantt
**Table existante**: `gantt_dependencies`
**Réutilisation**: ✅ OUI

**Champs**:
- `id`
- `task_id` → Tâche qui dépend
- `depends_on_task_id` → Tâche dont dépend
- `dependency_type` → FS/SS/FF/SF
- `lag_days` → Décalage en jours
- `timestamps`

**Aucune migration supplémentaire nécessaire** ✅

### 3. Projets/Phases
**Tables existantes**: `papas`, `papa_versions`, `objectifs`, `actions_prioritaires`
**Réutilisation**: ✅ OUI

**Hiérarchie**:
- `Papa` (Plan d'Action Prioritaire Annuel) → Projet principal
- `PapaVersion` → Version du projet
- `Objectif` → Phase/Objectif
- `ActionPrioritaire` → Phase/Action
- `Tache` → Tâche individuelle

**Mapping Gantt**:
- `Papa` → Project (niveau 0)
- `Objectif` → Phase (niveau 1)
- `ActionPrioritaire` → Phase (niveau 2)
- `Tache` → Task (niveau 3+)

### 4. Audit Logs
**Table existante**: `audit_logs` (si existe) OU créer `gantt_audit_logs`
**Décision**: Créer `gantt_audit_logs` pour audit spécifique Gantt

**Champs nécessaires**:
- `id`
- `user_id` → Utilisateur qui a modifié
- `task_id` → Tâche modifiée
- `action` → create/update/delete/reschedule
- `field_name` → Champ modifié (nullable)
- `old_value` → Ancienne valeur (nullable, JSON)
- `new_value` → Nouvelle valeur (nullable, JSON)
- `requires_approval` → Nécessite approbation
- `approved_by` → Approuvé par (nullable)
- `approved_at` → Date d'approbation (nullable)
- `timestamps`

**Migration nécessaire**: ✅ OUI

## Modèles Eloquent

### Modèles Existants à Réutiliser
1. `App\Models\Tache` → GanttTask
2. `App\Models\GanttDependency` → Dépendances
3. `App\Models\Papa` → Project
4. `App\Models\PapaVersion` → Project Version
5. `App\Models\Objectif` → Phase
6. `App\Models\ActionPrioritaire` → Phase

### Nouveaux Modèles à Créer
1. `App\Models\GanttAuditLog` → Audit spécifique Gantt

## Relations à Ajouter/Améliorer

### Dans `Tache` Model
```php
// Déjà présentes :
- dependencies() → hasMany(GanttDependency::class, 'task_id')
- dependentTasks() → hasMany(GanttDependency::class, 'depends_on_task_id')
- tacheParent() → belongsTo(Tache::class, 'tache_parent_id')
- sousTaches() → hasMany(Tache::class, 'tache_parent_id')
- responsable() → belongsTo(User::class, 'responsable_id')
- actionPrioritaire() → belongsTo(ActionPrioritaire::class)

// À ajouter :
- ganttAuditLogs() → hasMany(GanttAuditLog::class)
```

## API Resources

### `GanttTaskResource`
Transforme `Tache` en format JSON standard Gantt :
```json
{
  "id": "1",
  "name": "CODE - Libellé",
  "start": "2025-01-01",
  "end": "2025-01-31",
  "progress": 0.5,
  "dependencies": ["2", "3"],
  "responsible": "Nom User",
  "type": "task|milestone|phase",
  "color": "#3498db",
  "critical": false,
  "parent": "0",
  "baseline_start": "2025-01-01",
  "baseline_end": "2025-01-31",
  "actual_start": null,
  "actual_end": null
}
```

## Routes API

### Structure REST
```
GET    /api/projects/{papa}/gantt          → Liste tâches + méta
POST   /api/projects/{papa}/gantt/tasks    → Créer tâche
PUT    /api/gantt/tasks/{tache}            → Mettre à jour tâche
DELETE /api/gantt/tasks/{tache}            → Supprimer tâche
POST   /api/projects/{papa}/gantt/sync     → Sync bulk (drag/drop)
GET    /api/projects/{papa}/gantt/export   → Export PDF (optionnel)
```

**Note**: Utilisation de `{papa}` comme paramètre de projet car c'est l'entité racine dans l'architecture existante.

## Permissions RBAC

### Permissions à Créer
- `gantt.view` → Voir le Gantt
- `gantt.edit_dates` → Modifier les dates (drag/drop)
- `gantt.manage_dependencies` → Gérer les dépendances
- `gantt.export` → Exporter PDF/PNG
- `gantt.approve` → Approuver modifications sensibles

### Policies
- `GanttTaskPolicy` → Autorisation sur les tâches
- `ProjectPolicy` → Extensions pour `viewGantt`, `updateGantt`

## Avantages de cette Approche

1. ✅ **Pas de duplication de données** : Réutilisation des tables existantes
2. ✅ **Cohérence** : Architecture unifiée
3. ✅ **Maintenance simplifiée** : Un seul modèle pour les tâches
4. ✅ **Performance** : Pas de jointures supplémentaires
5. ✅ **Migration minimale** : Seulement `gantt_audit_logs` à créer

## Points d'Attention

1. ⚠️ **Hiérarchie complexe** : Gérer la hiérarchie PAPA → Version → Objectif → Action → Tâche
2. ⚠️ **Mapping type** : Déterminer si Objectif/Action = "phase" ou "task" dans le Gantt
3. ⚠️ **Performance** : Optimiser les requêtes avec eager loading pour éviter N+1
4. ⚠️ **Validation** : S'assurer que les dates respectent les dépendances

---

**Date**: 2025-01-01  
**Auteur**: Codex (AI Assistant)

