<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Documentation - SUIVI-PAPA CEEAC</title>

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
    </style>
</head>
<body>
    @include('partials.landing.navbar')

    <div class="container py-5 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-ceeac-blue mb-4">
                    <i class="bi bi-book me-2"></i>Documentation
                </h1>
                <p class="text-muted mb-5">Guide d'utilisation et documentation de la plateforme SUIVI-PAPA CEEAC</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac h-100">
                    <div class="card-body">
                        <div class="feature-icon feature-icon-blue mb-3">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h5 class="card-title">Guide de démarrage</h5>
                        <p class="card-text text-muted">Découvrez comment commencer à utiliser la plateforme et accéder à vos premiers modules.</p>
                        <a href="{{ route('ressources') }}" class="btn btn-primary btn-ceeac">
                            Consulter
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac h-100">
                    <div class="card-body">
                        <div class="feature-icon feature-icon-green mb-3">
                            <i class="bi bi-upload"></i>
                        </div>
                        <h5 class="card-title">Guide d'import</h5>
                        <p class="card-text text-muted">Apprenez à importer vos données Excel et à utiliser les modèles fournis.</p>
                        <a href="{{ route('ressources') }}" class="btn btn-primary btn-ceeac">
                            Consulter
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac h-100">
                    <div class="card-body">
                        <div class="feature-icon feature-icon-purple mb-3">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="card-title">Sécurité & Accès</h5>
                        <p class="card-text text-muted">Comprenez le système de sécurité, les rôles et les permissions.</p>
                        <a href="{{ route('ressources') }}" class="btn btn-primary btn-ceeac">
                            Consulter
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac h-100">
                    <div class="card-body">
                        <div class="feature-icon feature-icon-yellow mb-3">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h5 class="card-title">KPI & Tableaux de bord</h5>
                        <p class="card-text text-muted">Guide pour créer et personnaliser vos tableaux de bord et indicateurs.</p>
                        <a href="{{ route('ressources') }}" class="btn btn-primary btn-ceeac">
                            Consulter
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac h-100">
                    <div class="card-body">
                        <div class="feature-icon feature-icon-orange mb-3">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </div>
                        <h5 class="card-title">Export & Rapports</h5>
                        <p class="card-text text-muted">Découvrez comment générer et exporter vos rapports PDF et Excel.</p>
                        <a href="{{ route('ressources') }}" class="btn btn-primary btn-ceeac">
                            Consulter
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-ceeac h-100">
                    <div class="card-body">
                        <div class="feature-icon feature-icon-red mb-3">
                            <i class="bi bi-question-circle"></i>
                        </div>
                        <h5 class="card-title">FAQ</h5>
                        <p class="card-text text-muted">Consultez les questions fréquemment posées et leurs réponses.</p>
                        <a href="{{ route('landing') }}#faq" class="btn btn-primary btn-ceeac">
                            Consulter
                        </a>
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




