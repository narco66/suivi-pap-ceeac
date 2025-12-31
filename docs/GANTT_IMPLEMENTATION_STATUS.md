# Statut d'Implémentation - Module Gantt

**Date**: 2025-01-01  
**Phase**: Phase 1 MVP (en cours)  
**Statut**: ✅ Structure de base créée, Phase 1 MVP partiellement implémentée

## ✅ Fichiers Créés/Modifiés

### Migrations
- ✅ `database/migrations/2025_12_31_132540_create_gantt_audit_logs_table.php` - Table d'audit Gantt

### Modèles
- ✅ `app/Models/GanttAuditLog.php` - Modèle pour les logs d'audit
- ✅ `app/Models/Tache.php` - Relation `ganttAuditLogs()` ajoutée

### Policies
- ✅ `app/Policies/GanttTaskPolicy.php` - Policy complète avec toutes les méthodes

### Form Requests
- ✅ `app/Http/Requests/GanttTaskStoreRequest.php` - Validation création
- ✅ `app/Http/Requests/GanttTaskUpdateRequest.php` - Validation mise à jour
- ✅ `app/Http/Requests/GanttSyncRequest.php` - Validation synchronisation

### Controllers
- ✅ `app/Http/Controllers/Papa/GanttController.php` - Vue Blade principale
- ✅ `app/Http/Controllers/Api/GanttApiController.php` - API GET (liste tâches)
- ✅ `app/Http/Controllers/Api/GanttTaskController.php` - CRUD tâches (store/update/destroy)
- ✅ `app/Http/Controllers/Api/GanttSyncController.php` - Synchronisation bulk (Phase 2)

### Resources
- ✅ `app/Http/Resources/GanttTaskResource.php` - Format JSON standard Gantt

### Routes
- ✅ `routes/web.php` - Routes Gantt ajoutées (index + API)

### Vues
- ✅ `resources/views/gantt/index.blade.php` - Vue principale Gantt

### Assets Frontend
- ✅ `resources/js/gantt/index.js` - Initialisation Frappe Gantt (Phase 1)
- ✅ `resources/css/gantt.css` - Styles Gantt

### Documentation
- ✅ `docs/GANTT_MAPPING.md` - Mapping des tables existantes
- ✅ `docs/AUDIT_GANTT.md` - Audit de sécurité (ancien code)

## ⚠️ À Compléter/Corriger

### 1. Enregistrement de la Policy
**Fichier**: `app/Providers/AppServiceProvider.php`

La `GanttTaskPolicy` doit être enregistrée. Actuellement, `TachePolicy` est enregistrée, mais `GanttTaskPolicy` a des méthodes spécifiques (`viewGantt`, `editDates`, etc.).

**Action requise**: 
- Vérifier si `TachePolicy` doit être remplacée par `GanttTaskPolicy`
- OU ajouter les méthodes manquantes dans `TachePolicy`
- OU utiliser `Gate::define()` pour les méthodes spécifiques Gantt

### 2. Configuration Vite
**Fichier**: `vite.config.js`

Vérifier que `resources/js/gantt/index.js` et `resources/css/gantt.css` sont compilés par Vite.

**Action requise**:
```js
// Dans vite.config.js, s'assurer que :
input: [
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/js/gantt/index.js', // Ajouter si nécessaire
]
```

### 3. Permissions RBAC
**Action requise**: Créer les permissions suivantes dans la base de données :
- `gantt.view`
- `gantt.edit_dates`
- `gantt.manage_dependencies`
- `gantt.export`
- `gantt.approve`

**Commande suggérée**:
```php
// Dans un seeder ou tinker
Permission::create(['name' => 'gantt.view']);
Permission::create(['name' => 'gantt.edit_dates']);
Permission::create(['name' => 'gantt.manage_dependencies']);
Permission::create(['name' => 'gantt.export']);
Permission::create(['name' => 'gantt.approve']);
```

### 4. Correction URL API dans la Vue
**Fichier**: `resources/views/gantt/index.blade.php`

L'URL de l'API dans le script JS doit être corrigée pour utiliser dynamiquement le PAPA sélectionné.

**Action requise**: Corriger la construction de l'URL dans `resources/js/gantt/index.js`

### 5. Gestion des Erreurs API
**Fichiers**: Controllers API

Améliorer la gestion des erreurs pour retourner des messages plus clairs.

### 6. Tests
**Action requise**: Créer des tests unitaires et feature tests pour :
- Validation des dates
- Calcul des dépendances
- Détection de cycles
- Permissions RBAC

## 📋 Phase 1 MVP - Checklist

### Backend
- [x] Migration `gantt_audit_logs`
- [x] Modèle `GanttAuditLog`
- [x] Policy `GanttTaskPolicy`
- [x] Form Requests (Store, Update, Sync)
- [x] Controller `GanttController` (vue Blade)
- [x] Controller `GanttApiController` (API GET)
- [x] Controller `GanttTaskController` (CRUD)
- [x] Resource `GanttTaskResource`
- [x] Routes configurées
- [ ] Permissions créées dans DB
- [ ] Policy enregistrée correctement
- [ ] Tests unitaires

### Frontend
- [x] Vue Blade `gantt/index.blade.php`
- [x] JS `resources/js/gantt/index.js`
- [x] CSS `resources/css/gantt.css`
- [ ] Correction URL API dynamique
- [ ] Gestion erreurs améliorée
- [ ] Tests E2E

### Fonctionnalités Phase 1
- [x] Vue Gantt lecture seule
- [x] Timeline basique + zoom
- [x] Filtres simples (PAPA, Version)
- [x] API GET avec format JSON standard
- [ ] Affichage dépendances (visuel)
- [ ] Légende complète

## 🚀 Phase 2 - À Implémenter

### Drag & Drop
- [ ] Handler `on_date_change` dans Frappe Gantt
- [ ] Appel API `/api/projects/{papa}/gantt/sync`
- [ ] Rollback UI en cas d'erreur
- [ ] Toast notifications

### Dépendances
- [ ] Affichage visuel des dépendances
- [ ] Validation respect dépendances (FS, SS, FF, SF)
- [ ] Détection cycles dépendances
- [ ] Interface gestion dépendances

### Export PDF/PNG
- [ ] Intégration `html2canvas` + `jsPDF`
- [ ] Bouton export (si permission)
- [ ] Watermark institutionnel (optionnel)

### RBAC Complet
- [ ] Vérification permissions côté frontend
- [ ] Désactivation drag/drop si pas de permission
- [ ] Messages d'erreur appropriés

### Audit
- [ ] Logs d'audit complets
- [ ] Affichage historique modifications
- [ ] Interface consultation audit

## 🎯 Phase 3 - À Implémenter

### Baseline vs Actual
- [ ] Affichage baseline (barres grises)
- [ ] Affichage actual (barres colorées)
- [ ] Toggle baseline/actual
- [ ] Calcul écarts

### Chemin Critique
- [ ] Algorithme calcul chemin critique
- [ ] Affichage visuel chemin critique
- [ ] Toggle afficher/masquer
- [ ] Recalcul automatique

### Notifications Retards
- [ ] Job calcul retards
- [ ] Notifications in-app
- [ ] Notifications email
- [ ] Dashboard alertes retards

### Workflow Approbation
- [ ] Détection modifications sensibles
- [ ] Workflow approbation
- [ ] Interface approbation
- [ ] Notifications approbateurs

## 🔧 Corrections Immédiates Nécessaires

1. **URL API dynamique** : Corriger la construction de l'URL dans `index.js`
2. **Enregistrement Policy** : S'assurer que `GanttTaskPolicy` est utilisée
3. **Permissions** : Créer les permissions dans la base de données
4. **Vite Config** : Vérifier compilation JS/CSS

## 📝 Notes Techniques

### Structure Hiérarchique
- `Papa` → Project (niveau 0)
- `Objectif` → Phase (niveau 1)
- `ActionPrioritaire` → Phase (niveau 2)
- `Tache` → Task (niveau 3+)

### Format JSON Standard
Le `GanttTaskResource` retourne le format standard :
```json
{
  "id": "1",
  "name": "CODE - Libellé",
  "start": "2025-01-01",
  "end": "2025-01-31",
  "duration": 30,
  "progress": 0.5,
  "dependencies": ["2", "3"],
  "responsible": "Nom User",
  "type": "task|milestone|phase",
  "color": "#3498db",
  "critical": false,
  "parent": "0"
}
```

### Performance
- Limite de 500 tâches par défaut (Phase 1)
- Eager loading optimisé
- Index DB recommandés : `date_debut_prevue`, `date_fin_prevue`, `action_prioritaire_id`

---

**Prochaine étape**: Corriger les points critiques listés ci-dessus, puis tester la Phase 1 MVP.

