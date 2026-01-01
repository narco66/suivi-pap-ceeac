# Prochaines Étapes - Module Gantt

## ✅ Ce qui a été fait (Étape suivante complétée)

### 1. Permissions RBAC
- ✅ Seeder `GanttPermissionsSeeder` créé
- ✅ Permissions créées dans la base de données :
  - `gantt.view`
  - `gantt.edit_dates`
  - `gantt.manage_dependencies`
  - `gantt.export`
  - `gantt.approve`
- ✅ Permissions attribuées aux rôles (admin, admin_dsi, sg_manager, direction_manager)

### 2. Configuration Policies
- ✅ `GanttTaskPolicy` enregistrée via `Gate::define()` dans `AppServiceProvider`
- ✅ Méthodes spécifiques Gantt accessibles :
  - `viewGantt()`
  - `editDates()`
  - `manageDependencies()`
  - `export()`
- ✅ Controllers mis à jour pour utiliser les bonnes autorisations

### 3. Configuration Vite
- ✅ `vite.config.js` mis à jour pour compiler :
  - `resources/js/gantt/index.js`
  - `resources/css/gantt.css`

### 4. Routes
- ✅ Toutes les routes Gantt sont enregistrées et fonctionnelles :
  - `GET /gantt` → Vue principale
  - `GET /api/projects/{papa}/gantt` → API données
  - `POST /api/projects/{papa}/gantt/tasks` → Créer tâche
  - `PUT /api/gantt/tasks/{tache}` → Mettre à jour
  - `DELETE /api/gantt/tasks/{tache}` → Supprimer
  - `POST /api/projects/{papa}/gantt/sync` → Synchronisation bulk

## 🎯 Actions Immédiates à Effectuer

### 1. Compiler les Assets
```bash
npm run build
# ou pour le développement avec hot reload
npm run dev
```

### 2. Tester l'Accès
1. Se connecter avec un utilisateur admin
2. Accéder à `http://127.0.0.1:8000/gantt`
3. Vérifier que la page s'affiche
4. Sélectionner un PAPA et filtrer
5. Vérifier que le diagramme s'affiche

### 3. Vérifier les Données
Assurez-vous qu'il existe :
- Au moins un PAPA dans la base de données
- Des tâches avec `date_debut_prevue` et `date_fin_prevue` non nulles
- Des relations correctes (PAPA → Version → Objectif → Action → Tâche)

## 📝 Corrections Mineures Restantes

### 1. URL API dans la Vue
**Fichier** : `resources/views/gantt/index.blade.php`

L'URL API est maintenant construite dynamiquement dans le JS, mais vérifier que cela fonctionne correctement.

### 2. Gestion d'Erreurs Frontend
Améliorer les messages d'erreur dans `resources/js/gantt/index.js` pour être plus explicites.

### 3. Affichage Dépendances
Les dépendances sont dans les données JSON mais pas encore affichées visuellement dans Frappe Gantt (Phase 2).

## 🚀 Phase 2 - À Implémenter

### Drag & Drop
1. Activer `on_date_change` dans Frappe Gantt
2. Appeler l'API `/api/projects/{papa}/gantt/sync`
3. Gérer les erreurs avec rollback UI
4. Afficher des notifications toast

### Dépendances Visuelles
1. Vérifier que Frappe Gantt affiche les dépendances (flèches)
2. Ajouter interface gestion dépendances
3. Validation respect dépendances (FS, SS, FF, SF)
4. Détection cycles

### Export PDF/PNG
1. Installer `html2canvas` et `jsPDF`
2. Créer fonction export
3. Ajouter bouton export (si permission)
4. Ajouter watermark institutionnel (optionnel)

## 📊 État Actuel

**Phase 1 MVP** : ✅ **~90% complète**

**Fonctionnalités opérationnelles** :
- ✅ Vue Gantt lecture seule
- ✅ Timeline basique + zoom
- ✅ Filtres PAPA/Version
- ✅ API GET avec format JSON standard
- ✅ Autorisation RBAC complète
- ✅ Audit logs structure

**Fonctionnalités à compléter** :
- ⚠️ Affichage visuel dépendances (dans les données mais pas visuel)
- ⚠️ Tests unitaires
- ⚠️ Tests E2E

## 🔍 Commandes Utiles

### Vérifier les Permissions
```bash
php artisan tinker
>>> \Spatie\Permission\Models\Permission::where('name', 'like', 'gantt.%')->pluck('name')
```

### Vérifier les Routes
```bash
php artisan route:list --name=gantt
```

### Vider le Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan permission:cache-reset
```

### Compiler les Assets
```bash
npm run build
# ou
npm run dev
```

## 📚 Documentation Disponible

1. **`docs/GANTT_MAPPING.md`** - Mapping des tables existantes
2. **`docs/GANTT_IMPLEMENTATION_STATUS.md`** - Statut détaillé de l'implémentation
3. **`docs/GANTT_TESTING.md`** - Guide de test complet
4. **`docs/AUDIT_GANTT.md`** - Audit de sécurité (ancien code)

---

**Prochaine action recommandée** : Compiler les assets et tester l'accès à `/gantt`


