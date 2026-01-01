<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Landing Page Configuration - SUIVI-PAPA CEEAC
    |--------------------------------------------------------------------------
    |
    | Configuration pour la page d'accueil institutionnelle
    |
    */

    'hero' => [
        'title' => 'SUIVI-PAPA CEEAC',
        'subtitle' => 'Plateforme de suivi hiérarchisé en temps réel des Plans d\'Action Prioritaires',
        'description' => 'Suivez vos PAPA → objectifs → actions → tâches → KPI → alertes → reporting → audit',
    ],

    'cta' => [
        'primary' => [
            'label' => 'Se connecter',
            'route' => 'login',
            'icon' => 'bi-box-arrow-in-right',
        ],
        'secondary' => [
            'label' => 'Découvrir la plateforme',
            'action' => 'scroll',
            'target' => '#features',
        ],
        'tertiary' => [
            'label' => 'Importer un PAPA',
            'route' => 'login',
            'icon' => 'bi-upload',
        ],
        'download' => [
            'label' => 'Télécharger un exemple Excel',
            'route' => 'ressources',
            'icon' => 'bi-download',
        ],
    ],

    'security' => [
        'title' => 'Accès sécurisé',
        'features' => [
            'RBAC (Rôle-Based Access Control)',
            'Périmètre de sécurité défini',
            'Journalisation complète des actions',
            'Verrouillage des PAPA',
            'Conformité aux standards',
        ],
        'badges' => [
            ['label' => 'Normal', 'class' => 'badge-statut-en-cours'],
            ['label' => 'Vigilance', 'class' => 'badge-statut-planifiee'],
            ['label' => 'Critique', 'class' => 'badge-statut-en-retard'],
        ],
    ],

    'features' => [
        [
            'icon' => 'bi-file-earmark-excel',
            'title' => 'Import Excel PAPA',
            'description' => 'Importez vos Plans d\'Action Prioritaires depuis des fichiers Excel avec validation automatique',
            'color' => 'blue',
        ],
        [
            'icon' => 'bi-diagram-3',
            'title' => 'Gantt dynamique',
            'description' => 'Visualisez et planifiez vos actions avec des diagrammes de Gantt interactifs et dynamiques',
            'color' => 'green',
        ],
        [
            'icon' => 'bi-graph-up-arrow',
            'title' => 'KPI & Tableaux de bord',
            'description' => 'Tableaux de bord multi-niveaux avec indicateurs de performance en temps réel',
            'color' => 'purple',
        ],
        [
            'icon' => 'bi-bell',
            'title' => 'Alertes & Escalade',
            'description' => 'Système d\'alertes automatiques avec escalade hiérarchique pour les actions critiques',
            'color' => 'orange',
        ],
        [
            'icon' => 'bi-file-earmark-pdf',
            'title' => 'Reporting & Exports',
            'description' => 'Générez des rapports détaillés et exportez vos données au format PDF ou Excel',
            'color' => 'red',
        ],
        [
            'icon' => 'bi-shield-check',
            'title' => 'Audit & Rétention',
            'description' => 'Journalisation complète des actions avec rétention des données pour audit',
            'color' => 'yellow',
        ],
    ],

    'modules' => [
        [
            'name' => 'PAPA',
            'description' => 'Gestion des Plans d\'Action Prioritaires',
            'icon' => 'bi-file-text',
            'route' => 'login',
        ],
        [
            'name' => 'Objectifs',
            'description' => 'Définition et suivi des objectifs',
            'icon' => 'bi-bullseye',
            'route' => 'login',
        ],
        [
            'name' => 'Actions',
            'description' => 'Gestion des actions prioritaires',
            'icon' => 'bi-lightning',
            'route' => 'login',
        ],
        [
            'name' => 'Tâches',
            'description' => 'Suivi des tâches opérationnelles',
            'icon' => 'bi-list-check',
            'route' => 'login',
        ],
        [
            'name' => 'KPI',
            'description' => 'Indicateurs de performance',
            'icon' => 'bi-graph-up',
            'route' => 'login',
        ],
        [
            'name' => 'Alertes',
            'description' => 'Système d\'alertes et notifications',
            'icon' => 'bi-bell',
            'route' => 'login',
        ],
        [
            'name' => 'Audit',
            'description' => 'Journalisation et audit',
            'icon' => 'bi-shield-check',
            'route' => 'login',
        ],
    ],

    'resources' => [
        [
            'title' => 'Modèle Excel PAPA (exemple)',
            'description' => 'Template Excel pour l\'import de Plans d\'Action Prioritaires',
            'type' => 'excel',
            'version' => '1.0',
            'date' => '2024-01-15',
            'download' => '#',
        ],
        [
            'title' => 'Guide d\'import',
            'description' => 'Documentation complète pour l\'import de données Excel',
            'type' => 'pdf',
            'version' => '1.2',
            'date' => '2024-02-20',
            'download' => '#',
        ],
        [
            'title' => 'Guide utilisateur',
            'description' => 'Manuel d\'utilisation complet de la plateforme',
            'type' => 'pdf',
            'version' => '2.0',
            'date' => '2024-03-10',
            'download' => '#',
        ],
        [
            'title' => 'Charte sécurité / accès',
            'description' => 'Politique de sécurité et règles d\'accès à la plateforme',
            'type' => 'pdf',
            'version' => '1.5',
            'date' => '2024-01-30',
            'download' => '#',
        ],
        [
            'title' => 'Modèles de rapports PDF',
            'description' => 'Templates de rapports PDF personnalisables',
            'type' => 'zip',
            'version' => '1.0',
            'date' => '2024-02-15',
            'download' => '#',
        ],
    ],

    'faq' => [
        [
            'question' => 'Qui peut accéder à la plateforme ?',
            'answer' => 'L\'accès est réservé aux membres autorisés de la CEEAC. Les droits d\'accès sont gérés par le système RBAC (Rôle-Based Access Control) selon le périmètre défini pour chaque utilisateur.',
        ],
        [
            'question' => 'Comment importer un PAPA ?',
            'answer' => 'Utilisez le modèle Excel fourni dans la section Ressources. Remplissez le fichier selon le format défini, puis utilisez la fonction d\'import depuis votre tableau de bord. Le système valide automatiquement les données avant l\'import.',
        ],
        [
            'question' => 'Comment fonctionne l\'escalade des alertes ?',
            'answer' => 'Le système d\'escalade envoie automatiquement des notifications selon la criticité et les délais. Les alertes critiques sont remontées hiérarchiquement aux responsables concernés avec des notifications automatiques.',
        ],
        [
            'question' => 'Comment exporter mes données ?',
            'answer' => 'Depuis chaque module, utilisez le bouton "Exporter" pour générer des fichiers PDF ou Excel. Les exports incluent les filtres et périodes sélectionnés.',
        ],
        [
            'question' => 'Qui peut voir les journaux d\'audit ?',
            'answer' => 'Les journaux d\'audit sont accessibles uniquement aux administrateurs et aux responsables de sécurité. Toutes les actions sont journalisées pour garantir la traçabilité.',
        ],
    ],

    'contact' => [
        'title' => 'Support DSI',
        'email' => 'dsi@ceeac.org',
        'description' => 'Pour toute question technique ou demande d\'accès, contactez le support DSI.',
        'status_route' => 'status',
    ],

    'footer' => [
        'copyright' => '© ' . date('Y') . ' CEEAC - Communauté Économique des États de l\'Afrique Centrale',
        'disclaimer' => 'Document interne – Ne pas diffuser sans autorisation',
        'links' => [
            ['label' => 'Accueil', 'route' => 'landing'],
            ['label' => 'Ressources', 'route' => 'ressources'],
            ['label' => 'Documentation', 'route' => 'docs'],
            ['label' => 'Connexion', 'route' => 'login'],
        ],
    ],
];




