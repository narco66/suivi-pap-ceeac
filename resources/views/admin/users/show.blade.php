<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-person-circle me-2"></i>Détails de l'utilisateur : {{ $user->name }}
            </h2>
            <div>
                @can('update', $user)
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </a>
                @endcan
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Nom complet</label>
                            <div><strong>{{ $user->name }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Email</label>
                            <div><strong>{{ $user->email }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Matricule</label>
                            <div><strong>{{ $user->matricule ?? '-' }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Téléphone</label>
                            <div><strong>{{ $user->telephone ?? $user->phone ?? '-' }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Fonction</label>
                            <div><strong>{{ $user->fonction ?? $user->title ?? '-' }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Structure</label>
                            <div><strong>{{ $user->structure?->name ?? '-' }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Statut</label>
                            <div>
                                @if($user->isActive())
                                    <span class="badge bg-success">Actif</span>
                                @elseif($user->isSuspended())
                                    <span class="badge bg-warning">Suspendu</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Dernière connexion</label>
                            <div><strong>{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y à H:i') : 'Jamais' }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Membre depuis</label>
                            <div><strong>{{ $user->created_at->format('d/m/Y') }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Email vérifié</label>
                            <div>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Oui</span>
                                    <small class="text-muted d-block">{{ $user->email_verified_at->format('d/m/Y') }}</small>
                                @else
                                    <span class="badge bg-warning">Non</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Rôles</h5>
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary me-1 mb-2">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Aucun rôle assigné</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0"><i class="bi bi-key me-2"></i>Permissions</h5>
                </div>
                <div class="card-body">
                    <div class="small">
                        <strong>Total:</strong> {{ $user->getAllPermissions()->count() }} permission(s)
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



