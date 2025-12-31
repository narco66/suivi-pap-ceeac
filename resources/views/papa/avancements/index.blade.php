<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-graph-up me-2"></i>Avancements
            </h2>
            @can('create', \App\Models\Avancement::class)
                <a href="{{ route('avancements.create') }}" class="btn btn-primary btn-ceeac">
                    <i class="bi bi-plus-circle me-2"></i>Enregistrer un avancement
                </a>
            @endcan
        </div>
    </x-slot>

    <!-- Statistiques -->
    @if(isset($stats))
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-ceeac-blue mb-0">{{ $stats['total'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning mb-0">{{ $stats['en_attente'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">En attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success mb-0">{{ $stats['valides'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Validés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-danger mb-0">{{ $stats['rejetes'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Rejetés</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('avancements.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Commentaire, tâche...">
                </div>
                <div class="col-md-2">
                    <label for="tache_id" class="form-label">Tâche</label>
                    <select class="form-select" id="tache_id" name="tache_id">
                        <option value="">Toutes les tâches</option>
                        @foreach($taches ?? [] as $tache)
                            <option value="{{ $tache['id'] }}" {{ request('tache_id') == $tache['id'] ? 'selected' : '' }}>
                                {{ $tache['libelle'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="statut" class="form-label">Statut</label>
                    <select class="form-select" id="statut" name="statut">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="valide" {{ request('statut') === 'valide' ? 'selected' : '' }}>Validé</option>
                        <option value="rejete" {{ request('statut') === 'rejete' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="soumis_par_id" class="form-label">Soumis par</label>
                    <select class="form-select" id="soumis_par_id" name="soumis_par_id">
                        <option value="">Tous</option>
                        @foreach($utilisateurs ?? [] as $user)
                            <option value="{{ $user['id'] }}" {{ request('soumis_par_id') == $user['id'] ? 'selected' : '' }}>
                                {{ $user['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_debut" class="form-label">Date début</label>
                    <input type="date" class="form-control" id="date_debut" name="date_debut" 
                           value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_fin" class="form-label">Date fin</label>
                    <input type="date" class="form-control" id="date_fin" name="date_fin" 
                           value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-ceeac-primary">
                        <i class="bi bi-funnel me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('avancements.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des avancements -->
    <div class="card card-ceeac">
        <div class="card-body">
            @if($avancements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-ceeac table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Tâche</th>
                                <th>Pourcentage</th>
                                <th>Commentaire</th>
                                <th>Soumis par</th>
                                <th>Statut</th>
                                <th>Validé par</th>
                                <th>Fichier</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($avancements as $avancement)
                                <tr>
                                    <td>
                                        <strong>{{ $avancement->date_avancement->format('d/m/Y') }}</strong>
                                        <br><small class="text-muted">{{ $avancement->date_avancement->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($avancement->tache)
                                            <a href="{{ route('taches.show', $avancement->tache) }}" class="text-decoration-none">
                                                <strong>{{ $avancement->tache->code }}</strong>
                                            </a>
                                            <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($avancement->tache->libelle, 30) }}</small>
                                            @if($avancement->tache->actionPrioritaire)
                                                <br><small class="text-muted">
                                                    Action: {{ $avancement->tache->actionPrioritaire->code }}
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                <div class="progress-bar 
                                                    @if($avancement->pourcentage_avancement >= 100) bg-success
                                                    @elseif($avancement->pourcentage_avancement >= 50) bg-primary
                                                    @else bg-warning
                                                    @endif" 
                                                    role="progressbar" 
                                                    style="width: {{ $avancement->pourcentage_avancement }}%"
                                                    aria-valuenow="{{ $avancement->pourcentage_avancement }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                    {{ $avancement->pourcentage_avancement }}%
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($avancement->commentaire)
                                            {{ \Illuminate\Support\Str::limit($avancement->commentaire, 50) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($avancement->soumisPar)
                                            <strong>{{ $avancement->soumisPar->name }}</strong>
                                            <br><small class="text-muted">{{ $avancement->soumisPar->email }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statutBadge = match($avancement->statut) {
                                                'valide' => 'bg-success',
                                                'en_attente' => 'bg-warning',
                                                'rejete' => 'bg-danger',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $statutBadge }}">
                                            {{ ucfirst(str_replace('_', ' ', $avancement->statut ?? 'N/A')) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($avancement->validePar)
                                            <strong>{{ $avancement->validePar->name }}</strong>
                                            @if($avancement->date_validation)
                                                <br><small class="text-muted">{{ $avancement->date_validation->format('d/m/Y') }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($avancement->fichier_joint && $avancement->fichierJointExists())
                                            <a href="{{ $avancement->fichier_joint_url }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Télécharger">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @elseif($avancement->fichier_joint)
                                            <span class="text-muted" title="Fichier non disponible">
                                                <i class="bi bi-file-x"></i>
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view', $avancement)
                                                <a href="{{ route('avancements.show', $avancement) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @can('update', $avancement)
                                                <a href="{{ route('avancements.edit', $avancement) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $avancements->links() }}
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="mb-0">Aucun avancement enregistré</p>
                    @if(request()->hasAny(['search', 'statut', 'tache_id', 'soumis_par_id', 'date_debut', 'date_fin']))
                        <a href="{{ route('avancements.index') }}" class="btn btn-outline-secondary mt-3">
                            <i class="bi bi-x-circle me-2"></i>Réinitialiser les filtres
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
