# Guide de Démarrage Rapide - Module Gantt

## 🚀 Installation et Configuration

### 1. Migrations
```bash
php artisan migrate
```

### 2. Permissions
```bash
php artisan db:seed --class=GanttPermissionsSeeder
```

### 3. Compiler les Assets
```bash
npm install
npm run build
# ou pour le développement
npm run dev
```

### 4. Données de Démo (Optionnel)
```bash
# S'assurer d'avoir des tâches avec dates dans la base
php artisan db:seed --class=GanttDemoSeeder
```

## 📍 Accès au Module

**URL** : `http://127.0.0.1:8000/gantt`

**Permissions requises** :
- `gantt.view` pour voir le diagramme
- `gantt.edit_dates` pour modifier les dates (drag & drop - Phase 2)

## ✅ Vérifications Rapides

### Vérifier les Permissions
```bash
php artisan tinker
>>> \Spatie\Permission\Models\Permission::where('name', 'like', 'gantt.%')->pluck('name')
```

### Vérifier les Routes
```bash
php artisan route:list --name=gantt
```

### Vérifier les Données
```bash
php artisan tinker
>>> \App\Models\Tache::whereNotNull('date_debut_prevue')->whereNotNull('date_fin_prevue')->count()
```

## 🐛 Dépannage Rapide

### Page blanche
1. Vérifier les logs : `storage/logs/laravel.log`
2. Vérifier la console navigateur (F12)
3. Vérifier que les assets sont compilés : `npm run build`

### Erreur 403
1. Vérifier les permissions : `php artisan db:seed --class=GanttPermissionsSeeder`
2. Vider le cache : `php artisan permission:cache-reset`

### Aucune donnée
1. Vérifier qu'il existe des tâches avec dates
2. Vérifier que le PAPA sélectionné a des tâches
3. Exécuter le seeder de démo : `php artisan db:seed --class=GanttDemoSeeder`

## 📊 Structure des Données

Le module utilise les tables existantes :
- `taches` → Tâches Gantt
- `gantt_dependencies` → Dépendances entre tâches
- `gantt_audit_logs` → Logs d'audit

## 🎯 Fonctionnalités Phase 1 MVP

- ✅ Vue Gantt lecture seule
- ✅ Timeline configurable (jour/semaine/mois)
- ✅ Filtres PAPA/Version
- ✅ Affichage tâches, jalons, phases
- ✅ Couleurs selon criticité
- ✅ Dépendances (dans les données, affichage visuel Phase 2)

## 🔜 Phase 2 (À venir)

- Drag & drop dates
- Export PDF/PNG
- Gestion dépendances visuelle
- RBAC complet côté frontend

---

**Dernière mise à jour** : 2025-01-01

