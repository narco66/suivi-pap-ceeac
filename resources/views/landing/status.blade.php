<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Statut de la plateforme - SUIVI-PAPA CEEAC</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- CEEAC CSS -->
    <link rel="stylesheet" href="{{ asset('css/ceeac.css') }}">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
        }
        .navbar-ceeac {
            background-color: var(--ceeac-blue) !important;
        }
        .footer-ceeac {
            background-color: var(--ceeac-blue-dark);
            color: white;
            padding: 2rem 0;
        }
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .status-operational {
            background-color: var(--ceeac-green);
        }
        .status-degraded {
            background-color: var(--ceeac-yellow);
        }
        .status-down {
            background-color: var(--ceeac-red);
        }
    </style>
</head>
<body>
    @include('partials.landing.navbar')

    <div class="container py-5 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-ceeac-blue mb-4">
                    <i class="bi bi-activity me-2"></i>Statut de la plateforme
                </h1>
                <p class="text-muted mb-5">État des services et composants de la plateforme SUIVI-PAPA CEEAC</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="status-indicator status-operational"></span>
                            <h5 class="mb-0">Application Web</h5>
                        </div>
                        <p class="text-muted small mb-0">Opérationnel</p>
                        <p class="text-muted small mb-0">Dernière vérification : {{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="status-indicator status-operational"></span>
                            <h5 class="mb-0">Base de données</h5>
                        </div>
                        <p class="text-muted small mb-0">Opérationnel</p>
                        <p class="text-muted small mb-0">Temps de réponse : < 50ms</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="status-indicator status-operational"></span>
                            <h5 class="mb-0">Système d'authentification</h5>
                        </div>
                        <p class="text-muted small mb-0">Opérationnel</p>
                        <p class="text-muted small mb-0">RBAC actif</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="status-indicator status-operational"></span>
                            <h5 class="mb-0">Service d'export</h5>
                        </div>
                        <p class="text-muted small mb-0">Opérationnel</p>
                        <p class="text-muted small mb-0">PDF & Excel disponibles</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="status-indicator status-operational"></span>
                            <h5 class="mb-0">Système d'alertes</h5>
                        </div>
                        <p class="text-muted small mb-0">Opérationnel</p>
                        <p class="text-muted small mb-0">Notifications actives</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="status-indicator status-operational"></span>
                            <h5 class="mb-0">Journalisation</h5>
                        </div>
                        <p class="text-muted small mb-0">Opérationnel</p>
                        <p class="text-muted small mb-0">Audit trail actif</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card card-ceeac">
                    <div class="card-body">
                        <h5 class="mb-3">
                            <i class="bi bi-info-circle me-2"></i>Informations
                        </h5>
                        <p class="mb-2"><strong>Version de la plateforme :</strong> 1.0.0</p>
                        <p class="mb-2"><strong>Dernière mise à jour :</strong> {{ now()->format('d/m/Y') }}</p>
                        <p class="mb-0"><strong>Support :</strong> <a href="mailto:dsi@ceeac.org">dsi@ceeac.org</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.landing.footer')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>




