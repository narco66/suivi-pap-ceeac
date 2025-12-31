<?php

return [
    'items' => [
        [
            'label' => 'Tableau de bord',
            'icon' => 'bi-speedometer2',
            'route' => 'dashboard',
            'permission' => null, // Accessible à tous les utilisateurs authentifiés
            'active' => ['dashboard'],
        ],
        [
            'label' => 'Mon Profil',
            'icon' => 'bi-person-circle',
            'route' => 'profile.edit',
            'permission' => null, // Accessible à tous les utilisateurs authentifiés
            'active' => ['profile.*'],
        ],
        [
            'label' => 'Organisation & Référentiels',
            'icon' => 'bi-building',
            'permission' => null,
            'active' => ['commissaires.*', 'commissions.*', 'departements.*', 'directions-appui.*', 'directions-techniques.*'],
            'children' => [
                [
                    'label' => 'Commissaires',
                    'icon' => 'bi-person-badge',
                    'route' => 'commissaires.index',
                    'permission' => null,
                    'active' => ['commissaires.*'],
                ],
                [
                    'label' => 'Commissions',
                    'icon' => 'bi-people',
                    'route' => 'commissions.index',
                    'permission' => null,
                    'active' => ['commissions.*'],
                ],
                [
                    'label' => 'Départements',
                    'icon' => 'bi-building',
                    'route' => 'departements.index',
                    'permission' => null,
                    'active' => ['departements.*'],
                ],
                [
                    'label' => 'Directions d\'Appui',
                    'icon' => 'bi-briefcase',
                    'route' => 'directions-appui.index',
                    'permission' => null,
                    'active' => ['directions-appui.*'],
                ],
                [
                    'label' => 'Directions Techniques',
                    'icon' => 'bi-gear',
                    'route' => 'directions-techniques.index',
                    'permission' => null,
                    'active' => ['directions-techniques.*'],
                ],
            ],
        ],
        [
            'label' => 'Planification',
            'icon' => 'bi-file-text',
            'permission' => null,
            'active' => ['papa.*'],
            'children' => [
                [
                    'label' => 'Liste des PAPA',
                    'icon' => 'bi-list-ul',
                    'route' => 'papa.index',
                    'permission' => null,
                    'active' => ['papa.index', 'papa.show'],
                ],
                [
                    'label' => 'Créer un PAPA',
                    'icon' => 'bi-plus-circle',
                    'route' => 'papa.create',
                    'permission' => null,
                    'active' => ['papa.create', 'papa.edit'],
                ],
            ],
        ],
        [
            'label' => 'Activités & Tâches',
            'icon' => 'bi-list-check',
            'permission' => null,
            'active' => ['objectifs.*', 'actions-prioritaires.*', 'taches.*'],
            'children' => [
                [
                    'label' => 'Objectifs',
                    'icon' => 'bi-bullseye',
                    'route' => 'objectifs.index',
                    'permission' => null,
                    'active' => ['objectifs.*'],
                ],
                [
                    'label' => 'Actions prioritaires',
                    'icon' => 'bi-lightning-charge',
                    'route' => 'actions-prioritaires.index',
                    'permission' => null,
                    'active' => ['actions-prioritaires.*'],
                ],
                [
                    'label' => 'Tâches',
                    'icon' => 'bi-list-check',
                    'route' => 'taches.index',
                    'permission' => null,
                    'active' => ['taches.*'],
                ],
            ],
        ],
        [
            'label' => 'Diagramme de Gantt',
            'icon' => 'bi-diagram-3',
            'route' => 'gantt.index',
            'permission' => 'gantt.view',
            'active' => ['gantt.*'],
        ],
        [
            'label' => 'Suivi & Avancement',
            'icon' => 'bi-graph-up-arrow',
            'route' => 'avancements.index',
            'permission' => null,
            'active' => ['avancements.*'],
        ],
        [
            'label' => 'Indicateurs KPI',
            'icon' => 'bi-graph-up',
            'route' => 'kpi.index',
            'permission' => null,
            'active' => ['kpi.*'],
        ],
        [
            'label' => 'Alertes',
            'icon' => 'bi-bell',
            'route' => 'alertes.index',
            'permission' => null,
            'active' => ['alertes.*'],
            'badge' => 'alertes.count', // Optionnel : compteur d'alertes
        ],
        [
            'label' => 'Documents',
            'icon' => 'bi-folder',
            'route' => 'ressources',
            'permission' => null,
            'active' => ['ressources.*'],
        ],
        [
            'label' => 'Import/Export',
            'icon' => 'bi-arrow-left-right',
            'permission' => null,
            'active' => ['import.*', 'export.*'],
            'children' => [
                [
                    'label' => 'Importer',
                    'icon' => 'bi-upload',
                    'route' => 'import.index',
                    'permission' => null,
                    'active' => ['import.*'],
                ],
                [
                    'label' => 'Exporter',
                    'icon' => 'bi-download',
                    'route' => 'export.index',
                    'permission' => null,
                    'active' => ['export.*'],
                ],
            ],
        ],
        [
            'label' => 'Administration',
            'icon' => 'bi-shield-lock',
            'permission' => 'admin.access',
            'role' => ['admin', 'admin_dsi'], // Alternative : rôle requis
            'active' => ['admin.*'],
            'children' => [
                [
                    'label' => 'Utilisateurs',
                    'icon' => 'bi-people',
                    'route' => 'admin.users.index',
                    'permission' => null, // Utilise la policy UserPolicy
                    'active' => ['admin.users.*'],
                ],
                [
                    'label' => 'Rôles & Permissions',
                    'icon' => 'bi-person-badge',
                    'route' => 'admin.roles.index',
                    'permission' => 'viewAny admin.role',
                ],
                [
                    'label' => 'Structures',
                    'icon' => 'bi-building',
                    'route' => 'admin.structures.index',
                    'permission' => 'viewAny admin.structure',
                ],
                [
                    'label' => 'Paramètres',
                    'icon' => 'bi-sliders',
                    'route' => 'admin.settings.index',
                    'permission' => 'viewAny admin.setting',
                ],
                [
                    'label' => 'Ressources',
                    'icon' => 'bi-folder',
                    'route' => 'admin.ressources.index',
                    'permission' => 'viewAny admin.ressource',
                ],
                [
                    'label' => 'Journal d\'Audit',
                    'icon' => 'bi-journal-text',
                    'route' => 'admin.audit.index',
                    'permission' => 'viewAny admin.audit',
                ],
                [
                    'label' => 'Santé Système',
                    'icon' => 'bi-heart-pulse',
                    'route' => 'admin.system.health',
                    'permission' => 'admin.access',
                ],
            ],
        ],
    ],
];

