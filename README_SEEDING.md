# 🌱 Guide de Seeding - SUIVI-PAPA CEEAC

Ce document explique comment générer un dataset complet et réaliste pour l'application SUIVI-PAPA CEEAC.

## 📋 Vue d'ensemble

Le système de seeding génère un environnement de démo complet avec :

- **Référentiels institutionnels** : Départements, Directions, Commissions, Commissaires
- **Utilisateurs multi-rôles** : Présidence, SG, Commissaires, Directeurs, Points focaux, Audit, ACC, CFC, etc.
- **Hiérarchie PAPA complète** : PAPA → Versions → Objectifs → Actions → Tâches → Sous-tâches → KPI → Avancements
- **Alertes et anomalies** : Système d'alertes avec escalade hiérarchique
- **Journaux d'audit** : Traçabilité complète des opérations

## 🚀 Commandes de base

### Seeding complet (recommandé)

```bash
php artisan migrate:fresh --seed
```

Cette commande :
1. Supprime toutes les tables
2. Recrée les migrations
3. Lance tous les seeders dans l'ordre

### Seeding sans réinitialiser la base

```bash
php artisan db:seed
```

### Seeding d'un seeder spécifique

```bash
php artisan db:seed --class=ReferentielsSeeder
php artisan db:seed --class=UsersSeeder
php artisan db:seed --class=PapaHierarchieSeeder
php artisan db:seed --class=JournauxSeeder
```

## ⚙️ Configuration des volumes

Les volumes de données sont configurables via le fichier `config/seeding.php` ou les variables d'environnement.

### Fichier de configuration

Éditez `config/seeding.php` pour ajuster les volumes :

```php
'volumes' => [
    'papas' => 2,
    'objectifs_per_version' => 10,
    'actions_per_objectif' => 5,
    'taches_per_action' => 10,
    // ...
]
```

### Variables d'environnement

Vous pouvez aussi utiliser des variables d'environnement dans votre `.env` :

```env
SEED_PAPAS=2
SEED_OBJECTIFS_PER_VERSION=10
SEED_ACTIONS_PER_OBJECTIF=5
SEED_TACHES_PER_ACTION=10
SEED_JOURNAUX_TOTAL=5000
```

### Volumes par défaut

| Entité | Volume par défaut |
|--------|------------------|
| Départements | 6 |
| Directions Techniques | 10 |
| Directions d'Appui | 8 |
| Commissions | 4 |
| Commissaires | 4 |
| Utilisateurs (total) | ~42 |
| PAPA | 2 |
| Versions par PAPA | 2 |
| Objectifs par version | 10 |
| Actions par objectif | 5 |
| Tâches par action | 10 |
| Sous-tâches par tâche | 3 |
| KPI par action | 3 |
| Avancements par tâche | 12 (3 mois hebdo) |
| Alertes totales | 50 |
| Anomalies totales | 15 |
| Journaux total | 5000 |

## 👥 Utilisateurs de démo

Tous les utilisateurs de démo ont le même mot de passe par défaut : **`password`**

### Comptes principaux

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur DSI | `admin@ceeac.int` | `password` |
| Président | `president@ceeac.int` | `password` |
| Vice-Président | `vice-president@ceeac.int` | `password` |
| Secrétaire Général | `sg@ceeac.int` | `password` |
| Commissaire 1 | `commissaire1@ceeac.int` | `password` |
| Directeur | `directeur.DT-XX-XX@ceeac.int` | `password` |
| Point Focal | `point_focal1@ceeac.int` | `password` |
| Audit Interne | `audit@ceeac.int` | `password` |
| ACC | `acc@ceeac.int` | `password` |
| CFC | `cfc@ceeac.int` | `password` |

### Structure des emails

- **Présidence/Vice-Présidence/SG** : `[role]@ceeac.int`
- **Commissaires** : `commissaire[N]@ceeac.int`
- **Directeurs** : `directeur.[CODE-DIRECTION]@ceeac.int`
- **Points focaux** : `point_focal[N]@ceeac.int`
- **Audit/ACC/CFC** : `[role]@ceeac.int`

## 📊 Distribution des statuts

Le seeding génère automatiquement une distribution réaliste des statuts :

| Statut | Pourcentage |
|--------|-------------|
| À temps | 35% |
| Vigilance | 25% |
| Critique (retard > 30j) | 15% |
| Bloquées | 10% |
| Terminées | 15% |

### Distribution des criticités d'alertes

| Criticité | Pourcentage |
|-----------|-------------|
| Normal | 40% |
| Vigilance | 35% |
| Critique | 25% |

## 🎯 Scénarios générés

Le seeding crée automatiquement des cas d'école réalistes :

### ✅ Cas normaux (35%)
- Actions et tâches en cours, dans les temps
- Dates cohérentes
- Avancements réguliers

### ⚠️ Cas vigilance (25%)
- Retards modérés (< 30 jours)
- Alertes de type "échéance dépassée"
- Escalade au niveau direction

### 🔴 Cas critiques (15%)
- Retards > 30 jours
- Alertes critiques avec escalade SG/Commissaire/Présidence
- Actions bloquées nécessitant intervention

### 🚫 Cas bloqués (10%)
- Actions/tâches avec raison de blocage
- Statut "bloque" avec commentaire
- Nécessitent déblocage manuel

### ✅ Cas terminés (15%)
- Actions/tâches complétées
- Dates de fin réelles renseignées
- Avancement à 100%

## 📈 Hiérarchie PAPA générée

Pour chaque PAPA, la structure suivante est créée :

```
PAPA 2024/2025
├── Version 1 (verrouillée)
│   └── Objectif 1
│       └── Action Prioritaire 1
│           ├── KPI 1, 2, 3
│           └── Tâche 1
│               ├── Sous-tâche 1.1
│               ├── Sous-tâche 1.2
│               └── Avancements (hebdomadaires sur 3 mois)
│           └── Tâche 2
│               └── ...
│       └── Action Prioritaire 2
│           └── ...
│   └── Objectif 2
│       └── ...
└── Version 2 (active)
    └── ...
```

## 🔍 Types d'alertes générées

- **Échéance dépassée** : Tâches/actions avec date de fin passée
- **Retard critique** : Retards > 30 jours
- **Blocage** : Actions/tâches bloquées
- **Anomalie** : Incohérences détectées
- **Escalade** : Alertes nécessitant remontée hiérarchique
- **KPI non atteint** : Indicateurs en dessous de la cible

## 📝 Journaux d'audit

Le seeding génère un historique complet d'opérations :

- **Actions** : création, modification, suppression, changement_statut, verrouillage, export, etc.
- **Entités** : papa, papa_version, objectif, action_prioritaire, tache, kpi, alerte
- **Traçabilité** : utilisateur, IP, user agent, données avant/après

## 🔧 Personnalisation

### Modifier le seed pour reproductibilité

Le seed Faker est configuré dans `config/seeding.php` :

```php
'seed' => env('SEED_STABLE', 12345),
```

Changez cette valeur pour générer des données différentes mais reproductibles.

### Ajuster les volumes pour tests

Pour des tests rapides, réduisez les volumes :

```env
SEED_OBJECTIFS_PER_VERSION=5
SEED_ACTIONS_PER_OBJECTIF=3
SEED_TACHES_PER_ACTION=5
SEED_JOURNAUX_TOTAL=1000
```

Pour des tests de performance, augmentez-les :

```env
SEED_OBJECTIFS_PER_VERSION=20
SEED_ACTIONS_PER_OBJECTIF=10
SEED_TACHES_PER_ACTION=20
SEED_JOURNAUX_TOTAL=50000
```

## 🐛 Dépannage

### Erreur "Class not found"

Assurez-vous que tous les modèles existent :

```bash
php artisan model:show App\Models\Papa
```

### Erreur de clé étrangère

Vérifiez que les migrations sont à jour :

```bash
php artisan migrate:status
php artisan migrate
```

### Seeding trop lent

Réduisez les volumes ou utilisez des transactions par lots (déjà implémenté pour les journaux).

### Données incohérentes

Le seeding utilise un seed Faker stable. Pour régénérer des données différentes :

1. Changez `SEED_STABLE` dans `.env`
2. Relancez `php artisan migrate:fresh --seed`

## 📊 Résumé après seeding

Après le seeding, un résumé est affiché avec :

- Nombre d'entités créées par type
- Durée totale du seeding
- Identifiants de connexion

Exemple de sortie :

```
═══════════════════════════════════════════════════════════
📊 RÉSUMÉ DU DATASET GÉNÉRÉ
═══════════════════════════════════════════════════════════

+--------------------------+--------+
| Entité                   | Nombre |
+--------------------------+--------+
| Utilisateurs             | 42     |
| Départements             | 6      |
| Directions Techniques    | 10     |
| PAPA                     | 2      |
| Objectifs                | 40     |
| Actions Prioritaires     | 200    |
| Tâches                   | 2000   |
| KPI                      | 600    |
| Alertes                  | 50     |
| Journaux                 | 5000   |
+--------------------------+--------+

⏱️  Durée totale: 45.32 secondes

✅ Seeding terminé avec succès!
```

## 🎓 Bonnes pratiques

1. **Toujours utiliser `--fresh` en développement** pour éviter les conflits
2. **Sauvegarder avant seeding en production** (si nécessaire)
3. **Ajuster les volumes selon l'environnement** (dev/test/prod)
4. **Documenter les modifications** de volumes dans le projet
5. **Utiliser le seed stable** pour la reproductibilité des tests

## 📚 Fichiers de seeding

- `config/seeding.php` : Configuration des volumes
- `database/factories/*.php` : Factories pour tous les modèles
- `database/seeders/ReferentielsSeeder.php` : Référentiels institutionnels
- `database/seeders/UsersSeeder.php` : Utilisateurs avec rôles
- `database/seeders/PapaHierarchieSeeder.php` : Hiérarchie PAPA complète
- `database/seeders/JournauxSeeder.php` : Journaux d'audit
- `database/seeders/DatabaseSeeder.php` : Orchestrateur principal

## ✅ Validation

Après le seeding, vérifiez :

- [ ] Tous les utilisateurs peuvent se connecter
- [ ] Les dashboards affichent des données
- [ ] Les alertes sont visibles
- [ ] Les journaux d'audit sont consultables
- [ ] Les exports fonctionnent
- [ ] Les permissions RBAC sont respectées

---

**Note** : Ce système de seeding est conçu pour générer un environnement de démo réaliste. Pour la production, utilisez des données réelles via les imports ou l'interface d'administration.




