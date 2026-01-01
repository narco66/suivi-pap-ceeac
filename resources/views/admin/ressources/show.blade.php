<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-folder me-2"></i>Détails de la ressource : {{ $ressource->titre }}
            </h2>
            <div>
                @can('update', $ressource)
                    <a href="{{ route('admin.ressources.edit', $ressource) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </a>
                @endcan
                <a href="{{ route('admin.ressources.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Titre</label>
                            <div><strong>{{ $ressource->titre }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Version</label>
                            <div><span class="badge bg-info">{{ $ressource->version }}</span></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small">Description</label>
                            <div>{{ $ressource->description ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Type</label>
                            <div><span class="badge bg-secondary">{{ ucfirst($ressource->type) }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Catégorie</label>
                            <div><span class="badge bg-primary">{{ ucfirst($ressource->categorie) }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Fichier</label>
                            <div>
                                @if($ressource->nom_fichier_original)
                                    <code>{{ $ressource->nom_fichier_original }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Taille</label>
                            <div>{{ $ressource->taille_formatee }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Statut</label>
                            <div>
                                @if($ressource->est_actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                                @if($ressource->est_public)
                                    <span class="badge bg-primary ms-1">Public</span>
                                @else
                                    <span class="badge bg-warning ms-1">Privé</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Téléchargements</label>
                            <div><strong>{{ $ressource->nombre_telechargements }}</strong> fois</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Date de publication</label>
                            <div>{{ $ressource->date_publication ? $ressource->date_publication->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Créé par</label>
                            <div>{{ $ressource->creePar?->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Date de création</label>
                            <div>{{ $ressource->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark me-2"></i>Fichier</h5>
                </div>
                <div class="card-body text-center">
                    <i class="{{ $ressource->icone }} fs-1 mb-3"></i>
                    @if($ressource->fichierExists())
                        <p class="text-success mb-3">
                            <i class="bi bi-check-circle me-2"></i>Fichier disponible
                        </p>
                        <a href="{{ route('ressources.download', $ressource) }}" class="btn btn-ceeac-primary w-100">
                            <i class="bi bi-download me-2"></i>Télécharger
                        </a>
                    @else
                        <p class="text-danger mb-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>Fichier non disponible
                        </p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0"><i class="bi bi-link-45deg me-2"></i>Lien public</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Lien vers cette ressource :</p>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" 
                               value="{{ route('ressources.show', $ressource) }}" readonly id="publicLink">
                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('publicLink')">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            document.execCommand('copy');
            alert('Lien copié dans le presse-papiers !');
        }
    </script>
</x-app-layout>



