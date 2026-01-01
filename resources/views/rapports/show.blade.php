<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-file-earmark-text me-2"></i>{{ $rapport->titre }}
            </h2>
            <div class="d-flex gap-2">
                @can('update', $rapport)
                    <a href="{{ route('rapports.edit', $rapport) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </a>
                @endcan
                <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Messages flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card card-ceeac">
                <div class="card-header bg-ceeac-gradient text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informations du rapport
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4"><i class="bi bi-tag me-1"></i>Code</dt>
                        <dd class="col-sm-8"><strong>{{ $rapport->code }}</strong></dd>

                        <dt class="col-sm-4"><i class="bi bi-card-heading me-1"></i>Titre</dt>
                        <dd class="col-sm-8">{{ $rapport->titre }}</dd>

                        @if($rapport->description)
                        <dt class="col-sm-4"><i class="bi bi-file-text me-1"></i>Description</dt>
                        <dd class="col-sm-8">{{ $rapport->description }}</dd>
                        @endif

                        <dt class="col-sm-4"><i class="bi bi-tag me-1"></i>Type</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-info">{{ ucfirst($rapport->type) }}</span>
                        </dd>

                        <dt class="col-sm-4"><i class="bi bi-diagram-3 me-1"></i>Périmètre</dt>
                        <dd class="col-sm-8">
                            @if($rapport->scope_level)
                                @if($rapport->scope_level === 'GLOBAL')
                                    <span class="badge bg-primary" title="Rapport institutionnel global">Global</span>
                                @elseif($rapport->scope_level === 'SG')
                                    <span class="badge bg-success" title="Secrétaire Général - Directions d'Appui">Secrétaire Général</span>
                                @elseif($rapport->scope_level === 'COMMISSAIRE')
                                    <span class="badge bg-warning" title="Commissaire - Département Technique">Commissaire</span>
                                @endif
                            @else
                                <span class="text-muted">Non défini</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4"><i class="bi bi-file-earmark me-1"></i>Format</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-secondary">{{ strtoupper($rapport->format) }}</span>
                        </dd>

                        <dt class="col-sm-4"><i class="bi bi-calendar me-1"></i>Période</dt>
                        <dd class="col-sm-8">{{ ucfirst($rapport->periode) }}</dd>

                        @if($rapport->date_debut && $rapport->date_fin)
                        <dt class="col-sm-4"><i class="bi bi-calendar-range me-1"></i>Dates</dt>
                        <dd class="col-sm-8">
                            Du {{ $rapport->date_debut->format('d/m/Y') }} au {{ $rapport->date_fin->format('d/m/Y') }}
                        </dd>
                        @endif

                        <dt class="col-sm-4"><i class="bi bi-info-circle me-1"></i>Statut</dt>
                        <dd class="col-sm-8">
                            @if($rapport->statut === 'genere')
                                <span class="badge bg-success">Généré</span>
                            @elseif($rapport->statut === 'brouillon')
                                <span class="badge bg-warning">Brouillon</span>
                            @elseif($rapport->statut === 'envoye')
                                <span class="badge bg-primary">Envoyé</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($rapport->statut) }}</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4"><i class="bi bi-person me-1"></i>Créé par</dt>
                        <dd class="col-sm-8">{{ $rapport->creePar->name ?? '-' }}</dd>

                        <dt class="col-sm-4"><i class="bi bi-calendar-event me-1"></i>Créé le</dt>
                        <dd class="col-sm-8">{{ $rapport->created_at->format('d/m/Y à H:i') }}</dd>

                        @if($rapport->date_generation)
                        <dt class="col-sm-4"><i class="bi bi-clock me-1"></i>Généré le</dt>
                        <dd class="col-sm-8">{{ $rapport->date_generation->format('d/m/Y à H:i') }}</dd>
                        @endif

                        @if($rapport->fichier_genere && $rapport->est_disponible)
                        <dt class="col-sm-4"><i class="bi bi-file-earmark me-1"></i>Fichier</dt>
                        <dd class="col-sm-8">
                            {{ $rapport->taille_fichier_formatee }}
                            @can('download', $rapport)
                                <a href="{{ route('rapports.download', $rapport) }}" class="btn btn-sm btn-success ms-2">
                                    <i class="bi bi-download me-1"></i>Télécharger
                                </a>
                            @endcan
                        </dd>
                        @endif

                        @if($rapport->checksum)
                        <dt class="col-sm-4"><i class="bi bi-shield-check me-1"></i>Checksum</dt>
                        <dd class="col-sm-8">
                            <code class="small">{{ $rapport->checksum }}</code>
                            <small class="text-muted d-block">Vérification d'intégrité du fichier</small>
                        </dd>
                        @endif

                        @if($rapport->est_automatique)
                        <dt class="col-sm-4"><i class="bi bi-clock-history me-1"></i>Automatique</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-info">Oui</span> - {{ ucfirst($rapport->frequence_cron ?? 'N/A') }}
                        </dd>
                        @endif

                        @if($rapport->papa)
                        <dt class="col-sm-4"><i class="bi bi-file-text me-1"></i>PAPA associé</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('papa.show', $rapport->papa) }}">{{ $rapport->papa->code }} - {{ $rapport->papa->libelle }}</a>
                        </dd>
                        @endif

                        @if($rapport->objectif)
                        <dt class="col-sm-4"><i class="bi bi-bullseye me-1"></i>Objectif associé</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('objectifs.show', $rapport->objectif) }}">{{ $rapport->objectif->code }} - {{ $rapport->objectif->libelle }}</a>
                        </dd>
                        @endif
                    </dl>
                </div>
            </div>

            @if($rapport->notes)
            <div class="card card-ceeac mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-sticky me-2"></i>Notes internes
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $rapport->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions et statistiques -->
        <div class="col-md-4">
            <div class="card card-ceeac">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-gear me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    @if($rapport->statut === 'brouillon')
                        @can('generate', $rapport)
                            <form action="{{ route('rapports.generate', $rapport) }}" method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-ceeac w-100">
                                    <i class="bi bi-play-circle me-2"></i>Générer le rapport
                                </button>
                            </form>
                        @endcan
                    @elseif($rapport->statut === 'genere' && $rapport->est_disponible)
                        @can('download', $rapport)
                            <a href="{{ route('rapports.download', $rapport) }}" class="btn btn-success btn-ceeac w-100 mb-3">
                                <i class="bi bi-download me-2"></i>Télécharger le rapport
                            </a>
                        @endcan
                    @endif

                    @can('update', $rapport)
                        <a href="{{ route('rapports.edit', $rapport) }}" class="btn btn-outline-secondary w-100 mb-3">
                            <i class="bi bi-pencil me-2"></i>Modifier
                        </a>
                    @endcan

                    @can('delete', $rapport)
                        <form action="{{ route('rapports.destroy', $rapport) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-2"></i>Supprimer
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            <div class="card card-ceeac mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Vues</dt>
                        <dd class="col-sm-6"><strong>{{ $rapport->nombre_vues }}</strong></dd>

                        <dt class="col-sm-6">Téléchargements</dt>
                        <dd class="col-sm-6"><strong>{{ $rapport->nombre_telechargements }}</strong></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


