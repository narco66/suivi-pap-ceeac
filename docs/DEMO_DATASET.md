# Dataset de Démonstration - SUIVI-PAPA CEEAC

## Vue d'ensemble

Ce document décrit le dataset de démonstration généré pour tester toutes les fonctionnalités de l'application SUIVI-PAPA CEEAC.

## Génération du Dataset

### Commande principale

```bash
php artisan demo:seed --fresh
```

Options:
- `--fresh`: Supprime toutes les tables et réexécute les migrations
- `--force`: Force l'exécution en production (non recommandé)

### Étapes d'exécution

1. **Migrations**: Création/suppression des tables
2. **Permissions**: Création des permissions et assignation aux rôles
3. **Référentiels**: Départements, Directions, Commissions, Commissaires
4. **Utilisateurs**: Création des utilisateurs avec rôles
5. **Scénario A**: PAPA 2025 v1 (verrouillée) - **PRINCIPAL**
6. **Scénario B**: PAPA 2025 v2 (brouillon)
7. **Scénario C**: PAPA 2024 (archivée)
8. **Alertes**: Génération automatique des alertes
9. **Journaux**: Génération des journaux d'audit

---

## Scénarios de Démonstration

### Scénario A: PAPA 2025 v1 (verrouillée) - PRINCIPAL

**Objectif**: Dataset principal pour tester toutes les fonctionnalités en conditions réelles.

**Caractéristiques**:
- **PAPA**: 2025, statut `en_cours`
- **Version**: v1, `verrouillée` (date verrouillage: 15/12/2024)
- **Objectifs**: 15-20 objectifs
- **Actions Prioritaires**: 80-120 actions
- **Tâches**: 400-800 tâches (avec sous-tâches)
- **KPI**: 3-5 KPI par action
- **Avancements**: 12 avancements par tâche (3 mois hebdomadaires)

**Répartition des statuts**:
- 25% terminées
- 45% en cours
- 15% planifiées
- 10% en attente
- 5% annulées

**Retards**: 20-30% des actions/tâches en retard

**Utilisation**:
- Test du Gantt avec données complètes
- Test des dashboards multi-niveaux
- Test des exports/rapports
- Test des alertes et escalade
- Test des permissions par rôle

---

### Scénario B: PAPA 2025 v2 (brouillon)

**Objectif**: Tester l'édition et la comparaison de versions.

**Caractéristiques**:
- **Version**: v2, `brouillon` (non verrouillée)
- **Objectifs**: 8-12 objectifs
- **Actions Prioritaires**: 40-60 actions
- **Tâches**: 200-400 tâches
- **KPI**: 2-3 KPI par action

**Utilisation**:
- Test de l'édition (version non verrouillée)
- Test de la comparaison v1 vs v2
- Test des imports Excel
- Test des workflows de validation

---

### Scénario C: PAPA 2024 (archivée)

**Objectif**: Tester l'archivage, la rétention et les exports historiques.

**Caractéristiques**:
- **PAPA**: 2024, statut `cloture`
- **Version**: v1, `archivée`
- **Objectifs**: 10-15 objectifs (tous terminés ou annulés)
- **Actions Prioritaires**: 50-80 actions (toutes terminées)
- **Tâches**: 250-500 tâches (toutes terminées)

**Utilisation**:
- Test des exports historiques
- Test de l'archivage
- Test de la rétention des données
- Test des rapports annuels

---

## Volumes de Données Générés

| Entité | Volume | Détails |
|--------|--------|---------|
| **Utilisateurs** | 30-80 | Par rôle (Présidence, SG, Commissaires, Directeurs, Points focaux, Audit, ACC, CFC, Admin DSI) |
| **Départements** | 6 | Référentiels institutionnels |
| **Directions Techniques** | 10 | Référentiels institutionnels |
| **Directions d'Appui** | 8 | Référentiels institutionnels |
| **PAPA** | 2 | 2024 (archivé) + 2025 (en cours) |
| **Versions PAPA** | 3 | 2024 v1, 2025 v1, 2025 v2 |
| **Objectifs** | 30-50 | Répartis sur les 3 versions |
| **Actions Prioritaires** | 150-250 | Réparties sur les objectifs |
| **Tâches** | 800-1500 | Avec sous-tâches (0-3 par tâche) |
| **KPI** | 400-800 | 3-5 par action |
| **Avancements** | 5000-15000 | 12 par tâche (historique 3 mois) |
| **Alertes** | 50-100 | Automatiques (retards, KPI, blocages) |
| **Journaux** | 5000-30000 | Historique d'audit complet |

---

## Comptes de Démonstration

### Administrateurs

| Rôle | Email | Mot de passe | Permissions |
|------|-------|-------------|-------------|
| **Admin DSI** | `admin@ceeac.int` | `password` | Accès complet (CRUD + exports + audit) |

### Direction

| Rôle | Email | Mot de passe | Permissions |
|------|-------|-------------|-------------|
| **Président** | `president@ceeac.int` | `password` | Lecture complète, exports |
| **Vice-Président** | `vice-president@ceeac.int` | `password` | Lecture complète, exports |
| **Secrétaire Général** | `sg@ceeac.int` | `password` | CRUD (sauf suppression), imports/exports |
| **Commissaire** | `commissaire1@ceeac.int` | `password` | Lecture + modification départements |
| **Directeur** | `directeur.{direction}@ceeac.int` | `password` | Gestion complète de sa direction |

### Opérationnels

| Rôle | Email | Mot de passe | Permissions |
|------|-------|-------------|-------------|
| **Point Focal** | `point.focal.{n}@ceeac.int` | `password` | Gestion de ses tâches, avancements |
| **Audit Interne** | `audit@ceeac.int` | `password` | Lecture complète + exports + audit |
| **ACC** | `acc@ceeac.int` | `password` | Gestion des alertes |
| **CFC** | `cfc@ceeac.int` | `password` | Contrôle et validation |

---

## Scénarios de Test

### 1. Test du Gantt

**Objectif**: Vérifier la visualisation temporelle complète.

**Actions**:
1. Se connecter avec `admin@ceeac.int`
2. Aller sur `/gantt`
3. Filtrer par PAPA 2025 v1
4. Vérifier:
   - Affichage hiérarchique (PAPA → Objectifs → Actions → Tâches)
   - Couleurs par criticité
   - Barres de progression
   - Jalons (diamants)
   - Zoom (Jour, Semaine, Mois)

**Données attendues**:
- 15-20 objectifs visibles
- 80-120 actions visibles
- 400-800 tâches visibles
- Couleurs: Normal (bleu), Vigilance (jaune), Critique (rouge)

---

### 2. Test des Dashboards Multi-Niveaux

**Objectif**: Vérifier les vues selon les rôles.

**Actions**:
1. Se connecter avec différents rôles
2. Vérifier les dashboards:
   - **Présidence**: Vue globale, KPI agrégés
   - **SG**: Vue complète avec alertes critiques
   - **Directeur**: Vue de sa direction uniquement
   - **Point Focal**: Vue de ses tâches

**Données attendues**:
- KPI cards avec valeurs réalistes
- Alertes par criticité
- Graphiques d'avancement
- Tableaux filtrés selon périmètre

---

### 3. Test des Alertes et Escalade

**Objectif**: Vérifier la génération et l'escalade des alertes.

**Actions**:
1. Se connecter avec `acc@ceeac.int`
2. Aller sur `/alertes`
3. Vérifier:
   - Alertes de retard (tâches/actions en retard)
   - Alertes KPI (KPI sous seuil)
   - Alertes de blocage
   - Niveaux d'escalade (Direction → SG → Présidence)

**Données attendues**:
- 50-100 alertes générées
- Répartition: 40% normal, 35% vigilance, 25% critique
- Escalade automatique pour critiques

---

### 4. Test des Exports

**Objectif**: Vérifier les exports PDF/Excel/CSV.

**Actions**:
1. Se connecter avec `sg@ceeac.int`
2. Aller sur `/export`
3. Tester:
   - Export PAPA 2025 v1 (PDF)
   - Export Actions (Excel)
   - Export KPI (CSV)
   - Export Audit (PDF)

**Données attendues**:
- Fichiers générés correctement
- Données complètes et formatées
- Templates institutionnels respectés

---

### 5. Test des Imports Excel

**Objectif**: Vérifier l'import de fichiers Excel.

**Actions**:
1. Se connecter avec `admin@ceeac.int`
2. Aller sur `/import`
3. Tester:
   - Import PAPA (fichier de démo)
   - Vérifier le rapport d'import
   - Vérifier le verrouillage automatique

**Données attendues**:
- Import réussi avec rapport
- Validation des lignes
- Gestion des erreurs

---

### 6. Test des Permissions

**Objectif**: Vérifier les restrictions d'accès par rôle.

**Actions**:
1. Tester avec différents rôles:
   - **Point Focal**: Ne peut modifier que ses tâches
   - **Directeur**: Peut modifier sa direction uniquement
   - **Audit**: Lecture seule + exports
   - **Admin**: Accès complet

**Données attendues**:
- Restrictions respectées
- Messages d'erreur clairs
- Redirections appropriées

---

### 7. Test de l'Audit

**Objectif**: Vérifier les journaux d'audit.

**Actions**:
1. Se connecter avec `audit@ceeac.int`
2. Aller sur `/audit` (si disponible)
3. Vérifier:
   - Historique complet des actions
   - Filtres (date, utilisateur, entité)
   - Exports

**Données attendues**:
- 5000-30000 lignes d'audit
- Traçabilité complète
- Diff avant/après pour modifications

---

## Configuration

### Fichier de configuration

Le fichier `config/seeding.php` permet de paramétrer les volumes:

```php
'volumes' => [
    'objectifs_per_version' => env('SEED_OBJECTIFS_PER_VERSION', 10),
    'actions_per_objectif' => env('SEED_ACTIONS_PER_OBJECTIF', 5),
    'taches_per_action' => env('SEED_TACHES_PER_ACTION', 10),
    // ...
],
```

### Variables d'environnement

Vous pouvez surcharger les volumes via `.env`:

```env
SEED_OBJECTIFS_PER_VERSION=15
SEED_ACTIONS_PER_OBJECTIF=8
SEED_TACHES_PER_ACTION=12
SEED_JOURNAUX_TOTAL=10000
```

---

## Reproductibilité

Le dataset est **100% reproductible** grâce à:
- Seed fixe pour Faker (`config('seeding.seed', 12345)`)
- Ordre d'exécution déterminé
- Pas de dépendances aléatoires non contrôlées

Pour régénérer exactement les mêmes données:
```bash
php artisan demo:seed --fresh
```

---

## Checklist de Validation

Après génération, vérifier:

- [ ] Tous les PAPA créés (2024 + 2025)
- [ ] Toutes les versions créées (3 versions)
- [ ] Objectifs répartis correctement
- [ ] Actions avec directions assignées
- [ ] Tâches avec responsables
- [ ] KPI avec valeurs cohérentes
- [ ] Avancements historiques (3 mois)
- [ ] Alertes générées automatiquement
- [ ] Journaux d'audit volumineux
- [ ] Utilisateurs avec rôles corrects
- [ ] Permissions assignées

---

## Dépannage

### Erreur: "Table doesn't exist"
```bash
php artisan migrate:fresh
php artisan demo:seed
```

### Erreur: "Foreign key constraint"
Vérifier l'ordre des seeders dans `MasterDemoSeeder`.

### Erreur: "Duplicate entry"
Le seed fixe devrait éviter cela. Si problème, vérifier les factories.

### Performance lente
Réduire les volumes dans `config/seeding.php` ou `.env`.

---

## Support

Pour toute question ou problème:
- Consulter `docs/AUDIT_REPORT.md`
- Consulter `docs/SEEDING_SCHEMA.md`
- Vérifier les logs: `storage/logs/laravel.log`



