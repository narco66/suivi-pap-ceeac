# Module Diagramme de Gantt - Documentation Technique

## Vue d'ensemble

Le module Gantt permet de visualiser et gérer le planning des Plans d'Action Prioritaires (PAPA) avec une interface interactive basée sur Frappe Gantt.

## Architecture

### Base de données

#### Table `gantt_dependencies`
- `id` : Identifiant unique
- `task_id` : ID de la tâche qui dépend
- `depends_on_task_id` : ID de la tâche dont dépend la tâche
- `dependency_type` : Type de dépendance (FS, SS, FF, SF)
- `lag_days` : Délai en jours (positif ou négatif)
- `created_at`, `updated_at` : Timestamps

#### Colonnes ajoutées à `taches`
- `baseline_start` : Date de début baseline (pour comparaison)
- `baseline_end` : Date de fin baseline
- `gantt_color` : Couleur personnalisée (hex)
- `gantt_sort_order` : Ordre de tri
- `is_critical` : Indicateur de chemin critique
- `gantt_notes` : Notes Gantt

### Modèles

#### `GanttDependency`
- Relations : `task()`, `dependsOnTask()`
- Méthode : `getDependencyTypes()` - Liste des types disponibles

#### `Tache` (extensions)
- Relations ajoutées :
  - `dependencies()` : Dépendances de cette tâche
  - `dependentTasks()` : Tâches qui dépendent de cette tâche

### API Resources

#### `GanttTaskResource`
Transforme les modèles `Tache` en format JSON standard pour le Gantt :
```json
{
  "id": "1",
  "name": "Code - Libellé",
  "start": "2024-01-01",
  "end": "2024-01-31",
  "duration": 30,
  "progress": 0.5,
  "dependencies": ["2", "3"],
  "responsible": "Nom",
  "type": "task|milestone",
  "color": "#0d6efd",
  "critical": false,
  "parent": "0"
}
```

### Controllers

#### `GanttController` (Papa)
- `index()` : Affiche la vue Gantt avec filtres
- Utilise le nouveau `GanttApiController` pour les données

#### `GanttApiController` (Api)
- `show()` : Retourne les données Gantt en JSON
- Filtres : papa_id, version_id, objectif_id, action_id
- Format : `{ data: [...], links: [...], meta: {...} }`

#### `GanttTaskController` (Api)
- `store()` : Créer une tâche depuis le Gantt
- `update()` : Mettre à jour une tâche
- `destroy()` : Supprimer une tâche

#### `GanttSyncController` (Api)
- `sync()` : Synchronisation en masse (drag & drop)

### Routes

```php
// Vue
GET /gantt → GanttController@index

// API
GET /gantt/data → GanttApiController@show
POST /gantt/tasks → GanttTaskController@store
PUT /gantt/tasks/{tache} → GanttTaskController@update
DELETE /gantt/tasks/{tache} → GanttTaskController@destroy
POST /gantt/sync → GanttSyncController@sync
```

### Form Requests

- `GanttTaskStoreRequest` : Validation création
- `GanttTaskUpdateRequest` : Validation mise à jour
- `GanttSyncRequest` : Validation synchronisation

### Policies

- `GanttTaskPolicy` : Permissions Gantt
  - `viewGantt()` : Voir le Gantt
  - `editDates()` : Modifier les dates
  - `manageDependencies()` : Gérer les dépendances
  - `export()` : Exporter

### Frontend

#### Vue : `resources/views/papa/gantt/index.blade.php`
- Filtres : PAPA, Version
- Légende des couleurs
- Conteneur Gantt avec Frappe Gantt
- Responsive

#### JavaScript
- Initialisation Frappe Gantt
- Chargement données via API
- Conversion format API → Frappe Gantt
- Gestion filtres
- Callbacks : click, date_change, progress_change

## Phase 1 (MVP) - Statut : ✅ COMPLÉTÉ

### Fonctionnalités implémentées
- ✅ Vue Gantt lecture seule
- ✅ Timeline configurable (jour/semaine/mois)
- ✅ Affichage tâches avec hiérarchie
- ✅ Barres avec progression
- ✅ Jalons (losanges)
- ✅ Filtres basiques (PAPA, Version)
- ✅ API GET pour récupération données
- ✅ Format JSON standard via GanttTaskResource
- ✅ Couleurs selon criticité
- ✅ Intégration menu navigation

### À implémenter (Phase 2)
- ⏳ Drag & drop dates
- ⏳ Synchronisation backend
- ⏳ Dépendances visuelles
- ⏳ Export PDF/PNG
- ⏳ Validation dépendances

### À implémenter (Phase 3)
- ⏳ Baseline vs Actual
- ⏳ Chemin critique
- ⏳ Notifications retards
- ⏳ Workflow approbation

## Utilisation

### Accès
Menu **PAPA → Vue Gantt** ou directement `/gantt`

### Filtres
1. Sélectionner un PAPA (optionnel)
2. Sélectionner une Version (optionnel)
3. Cliquer sur "Filtrer"

### Navigation
- Zoom : Utiliser les contrôles Frappe Gantt
- Vue : Jour / Semaine / Mois / Trimestre
- Scroll : Horizontal et vertical

## Tests

### Tests manuels recommandés
1. Charger le Gantt avec différents filtres
2. Vérifier l'affichage des tâches
3. Vérifier les jalons (losanges)
4. Vérifier les couleurs selon criticité
5. Tester le zoom
6. Tester la navigation

## Prochaines étapes

1. **Phase 2** : Implémenter drag & drop et synchronisation
2. **Phase 3** : Ajouter baseline, chemin critique, notifications
3. **Tests** : Créer tests unitaires et feature tests
4. **Seeder** : Créer données de démo (50-150 tâches)

