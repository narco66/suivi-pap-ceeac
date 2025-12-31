# Guide de Test - Module Gantt Phase 1 MVP

## ✅ Étape 1 : Vérifications Préalables

### 1.1 Permissions RBAC
Les permissions suivantes doivent être créées dans la base de données :
- ✅ `gantt.view`
- ✅ `gantt.edit_dates`
- ✅ `gantt.manage_dependencies`
- ✅ `gantt.export`
- ✅ `gantt.approve`

**Vérification** :
```bash
php artisan tinker
>>> \Spatie\Permission\Models\Permission::where('name', 'like', 'gantt.%')->pluck('name')
```

### 1.2 Migration
La table `gantt_audit_logs` doit exister :
```bash
php artisan migrate:status
```

### 1.3 Compilation Assets
Compiler les assets Vite :
```bash
npm run build
# ou pour le développement
npm run dev
```

## 🧪 Étape 2 : Tests Fonctionnels

### 2.1 Accès à la Page Gantt
**URL** : `http://127.0.0.1:8000/gantt`

**Scénario** :
1. Se connecter avec un utilisateur ayant la permission `gantt.view`
2. Accéder à `/gantt`
3. Vérifier que la page s'affiche sans erreur
4. Vérifier que les filtres PAPA et Version sont présents

**Résultat attendu** :
- ✅ Page s'affiche correctement
- ✅ Filtres fonctionnels
- ✅ Message "Veuillez sélectionner un PAPA" si aucun PAPA sélectionné

### 2.2 Affichage des Données
**Scénario** :
1. Sélectionner un PAPA dans le filtre
2. Cliquer sur "Filtrer"
3. Vérifier que le diagramme de Gantt s'affiche

**Résultat attendu** :
- ✅ Diagramme Frappe Gantt s'affiche
- ✅ Tâches visibles avec barres horizontales
- ✅ Jalons affichés comme losanges
- ✅ Couleurs selon la criticité
- ✅ Timeline fonctionnelle (jour/semaine/mois)

### 2.3 API Endpoint
**URL** : `GET /api/projects/{papa}/gantt?version_id=X`

**Test avec cURL** :
```bash
curl -X GET "http://127.0.0.1:8000/api/projects/1/gantt" \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -H "Cookie: [session_cookie]"
```

**Résultat attendu** :
```json
{
  "data": [
    {
      "id": "1",
      "name": "CODE - Libellé",
      "start": "2025-01-01",
      "end": "2025-01-31",
      "duration": 30,
      "progress": 0.5,
      "dependencies": ["2", "3"],
      "responsible": "Nom User",
      "type": "task",
      "color": "#0d6efd",
      "critical": false,
      "parent": "0"
    }
  ],
  "meta": {
    "min_date": "2025-01-01",
    "max_date": "2025-12-31",
    "total_tasks": 10,
    "editable": true
  }
}
```

### 2.4 Permissions
**Scénario** :
1. Se connecter avec un utilisateur SANS permission `gantt.view`
2. Tenter d'accéder à `/gantt`
3. Vérifier le message d'erreur 403

**Résultat attendu** :
- ✅ Erreur 403 "This action is unauthorized"
- ✅ Message d'erreur clair

## 🔍 Étape 3 : Tests de Performance

### 3.1 Charge de Données
**Test** : Charger un PAPA avec ~500 tâches

**Vérifications** :
- Temps de réponse API < 2 secondes
- Pas d'erreur mémoire
- Affichage fluide du diagramme

### 3.2 Requêtes SQL
**Vérification** : Activer le query log
```php
DB::enableQueryLog();
// ... appel API ...
dd(DB::getQueryLog());
```

**Résultat attendu** :
- Nombre de requêtes SQL < 10
- Eager loading fonctionnel (pas de N+1)

## 🐛 Étape 4 : Dépannage

### Problème : Page blanche
**Solutions** :
1. Vérifier les logs Laravel : `storage/logs/laravel.log`
2. Vérifier la console navigateur (F12)
3. Vérifier que Frappe Gantt est chargé : `typeof Gantt !== 'undefined'`

### Problème : Erreur 403
**Solutions** :
1. Vérifier que l'utilisateur a la permission `gantt.view`
2. Vérifier que les permissions sont créées : `php artisan db:seed --class=GanttPermissionsSeeder`
3. Vérifier le cache des permissions : `php artisan permission:cache-reset`

### Problème : Aucune donnée affichée
**Solutions** :
1. Vérifier qu'il existe des tâches avec `date_debut_prevue` et `date_fin_prevue` non nulles
2. Vérifier que le PAPA sélectionné a des tâches
3. Vérifier la console navigateur pour les erreurs API
4. Vérifier les logs Laravel pour les erreurs serveur

### Problème : Assets non chargés
**Solutions** :
1. Compiler les assets : `npm run build` ou `npm run dev`
2. Vérifier `vite.config.js` : les fichiers doivent être dans `input`
3. Vérifier que `@vite()` est présent dans la vue

## 📋 Checklist de Validation Phase 1 MVP

### Backend
- [x] Migration `gantt_audit_logs` exécutée
- [x] Permissions créées
- [x] Policy enregistrée
- [x] Routes configurées
- [x] Controllers fonctionnels
- [ ] Tests unitaires créés

### Frontend
- [x] Vue Blade créée
- [x] JS Frappe Gantt intégré
- [x] CSS styles appliqués
- [x] Filtres fonctionnels
- [ ] Affichage dépendances (visuel)
- [ ] Zoom fonctionnel

### Fonctionnalités
- [x] Vue lecture seule
- [x] Timeline basique
- [x] Filtres PAPA/Version
- [x] API GET avec format JSON
- [ ] Affichage dépendances
- [ ] Légende complète

## 🚀 Prochaines Étapes (Phase 2)

1. **Drag & Drop** : Implémenter la synchronisation des dates
2. **Dépendances** : Affichage visuel et gestion
3. **Export PDF/PNG** : Intégration html2canvas + jsPDF
4. **RBAC Complet** : Vérifications côté frontend
5. **Audit** : Interface consultation logs

---

**Date de création** : 2025-01-01  
**Dernière mise à jour** : 2025-01-01

