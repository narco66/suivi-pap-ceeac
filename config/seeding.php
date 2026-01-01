<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration des volumes de seeding
    |--------------------------------------------------------------------------
    |
    | Cette configuration permet de paramétrer les volumes de données
    | générées lors du seeding pour créer un environnement de démo réaliste.
    |
    */

    'volumes' => [
        // Référentiels institutionnels
        'departements' => env('SEED_DEPARTEMENTS', 6),
        'directions_techniques' => env('SEED_DIRECTIONS_TECHNIQUES', 10),
        'directions_appui' => env('SEED_DIRECTIONS_APPUI', 8),
        'commissions' => env('SEED_COMMISSIONS', 4),
        'commissaires' => env('SEED_COMMISSAIRES', 4),

        // Utilisateurs par rôle
        'users_presidence' => env('SEED_USERS_PRESIDENCE', 1),
        'users_vice_presidence' => env('SEED_USERS_VICE_PRESIDENCE', 1),
        'users_secretaires_generaux' => env('SEED_USERS_SG', 1),
        'users_commissaires' => env('SEED_USERS_COMMISSAIRES', 4),
        'users_directeurs' => env('SEED_USERS_DIRECTEURS', 8),
        'users_points_focaux' => env('SEED_USERS_POINTS_FOCAUX', 20),
        'users_audit_interne' => env('SEED_USERS_AUDIT', 2),
        'users_acc' => env('SEED_USERS_ACC', 1),
        'users_cfc' => env('SEED_USERS_CFC', 1),
        'users_bureau_liaison' => env('SEED_USERS_BUREAU_LIAISON', 2),
        'users_admin_dsi' => env('SEED_USERS_ADMIN_DSI', 1),

        // PAPA & hiérarchie
        'papas' => env('SEED_PAPAS', 2),
        'versions_per_papa' => env('SEED_VERSIONS_PER_PAPA', 2),
        'objectifs_per_version' => env('SEED_OBJECTIFS_PER_VERSION', 10),
        'actions_per_objectif' => env('SEED_ACTIONS_PER_OBJECTIF', 5),
        'taches_per_action' => env('SEED_TACHES_PER_ACTION', 10),
        'sous_taches_per_tache' => env('SEED_SOUS_TACHES_PER_TACHE', 3),
        'kpis_per_action' => env('SEED_KPIS_PER_ACTION', 3),
        'avancements_per_tache' => env('SEED_AVANCEMENTS_PER_TACHE', 12), // 3 mois hebdo
        'alertes_total' => env('SEED_ALERTES_TOTAL', 50),
        'anomalies_total' => env('SEED_ANOMALIES_TOTAL', 15),
        'journaux_total' => env('SEED_JOURNAUX_TOTAL', 5000),

        // Répartition des statuts (pourcentages)
        'statuts' => [
            'a_temps' => 35,      // 35% à temps
            'vigilance' => 25,     // 25% en vigilance
            'critique' => 15,      // 15% critique (retard > 30j)
            'bloque' => 10,        // 10% bloquées
            'termine' => 15,       // 15% terminées
        ],

        // Répartition des criticités d'alertes
        'criticites' => [
            'normal' => 40,
            'vigilance' => 35,
            'critique' => 25,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Dates de référence pour le seeding
    |--------------------------------------------------------------------------
    */
    'dates' => [
        'papa_2024_debut' => '2024-01-01',
        'papa_2024_fin' => '2024-12-31',
        'papa_2025_debut' => '2025-01-01',
        'papa_2025_fin' => '2025-12-31',
        'avancements_debut' => '-6 months', // 6 mois d'historique
        'avancements_fin' => 'now',
    ],

    /*
    |--------------------------------------------------------------------------
    | Mots de passe de démo
    |--------------------------------------------------------------------------
    */
    'demo_passwords' => [
        'default' => 'password', // Mot de passe par défaut pour tous les utilisateurs de démo
    ],

    /*
    |--------------------------------------------------------------------------
    | Seed stable (reproductible)
    |--------------------------------------------------------------------------
    */
    'seed' => env('SEED_STABLE', 12345), // Seed pour Faker (reproductibilité)
];




