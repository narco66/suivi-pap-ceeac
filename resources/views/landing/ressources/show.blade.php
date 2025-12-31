<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $ressource->titre }} - Ressources SUIVI-PAPA CEEAC</title>

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
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ressources') }}">Ressources</a></li>
                        <li class="breadcrumb-item active">{{ $ressource->titre }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-4">
                            <i class="{{ $ressource->icone }} fs-1 me-4"></i>
                            <div class="flex-grow-1">
                                <h1 class="h3 mb-3">{{ $ressource->titre }}</h1>
                                <div class="mb-3">
                                    <span class="badge bg-secondary me-2">{{ ucfirst($ressource->categorie) }}</span>
                                    <span class="badge bg-info me-2">Version {{ $ressource->version }}</span>
                                    <span class="badge bg-primary">{{ ucfirst($ressource->type) }}</span>
                                </div>
                            </div>
                        </div>

                        @if($ressource->description)
                        <div class="mb-4">
                            <h5 class="mb-2">Description</h5>
                            <p class="text-muted">{{ $ressource->description }}</p>
                        </div>
                        @endif

                        <div class="row g-3 mb-4">
                            @if($ressource->taille_fichier)
                            <div class="col-md-6">
                                <strong>Taille du fichier:</strong>
                                <span class="text-muted">{{ $ressource->taille_formatee }}</span>
                            </div>
                            @endif
                            @if($ressource->date_publication)
                            <div class="col-md-6">
                                <strong>Date de publication:</strong>
                                <span class="text-muted">{{ $ressource->date_publication->format('d/m/Y') }}</span>
                            </div>
                            @endif
                            @if($ressource->creePar)
                            <div class="col-md-6">
                                <strong>Publié par:</strong>
                                <span class="text-muted">{{ $ressource->creePar->name }}</span>
                            </div>
                            @endif
                            @if($ressource->nombre_telechargements > 0)
                            <div class="col-md-6">
                                <strong>Téléchargements:</strong>
                                <span class="text-muted">{{ $ressource->nombre_telechargements }} fois</span>
                            </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2 d-md-flex">
                            @if($ressource->fichierExists())
                                <a href="{{ route('ressources.download', $ressource) }}" class="btn btn-ceeac-primary btn-lg">
                                    <i class="bi bi-download me-2"></i>Télécharger le fichier
                                </a>
                            @else
                                <button class="btn btn-secondary btn-lg" disabled>
                                    <i class="bi bi-exclamation-triangle me-2"></i>Fichier non disponible
                                </button>
                            @endif
                            <a href="{{ route('ressources') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-ceeac-blue text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong>Type:</strong><br>
                                <span class="text-muted">{{ ucfirst($ressource->type) }}</span>
                            </li>
                            <li class="mb-2">
                                <strong>Catégorie:</strong><br>
                                <span class="text-muted">{{ ucfirst($ressource->categorie) }}</span>
                            </li>
                            <li class="mb-2">
                                <strong>Version:</strong><br>
                                <span class="text-muted">{{ $ressource->version }}</span>
                            </li>
                            @if($ressource->nom_fichier_original)
                            <li class="mb-2">
                                <strong>Fichier:</strong><br>
                                <span class="text-muted small">{{ $ressource->nom_fichier_original }}</span>
                            </li>
                            @endif
                        </ul>
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


