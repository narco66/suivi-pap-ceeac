<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-person-circle me-2"></i>Mon Profil
                </h2>
                <p class="text-muted mb-0 small">Gérez vos informations personnelles et vos préférences</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour au tableau de bord
            </a>
        </div>
    </x-slot>

    <!-- Messages de succès/erreur -->
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>Vos informations ont été mises à jour avec succès.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>Votre mot de passe a été mis à jour avec succès.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Colonne gauche - Informations du profil -->
        <div class="col-lg-8">
            <!-- Informations du profil -->
            <div class="card mb-4">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person me-2"></i>Informations du profil
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="card mb-4">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i>Modifier le mot de passe
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Suppression du compte -->
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>Zone de danger
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

        <!-- Colonne droite - Rôles et permissions -->
        <div class="col-lg-4">
            <!-- Informations de compte -->
            <div class="card mb-4">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informations du compte
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Email vérifié</label>
                        <div>
                            @if ($user->email_verified_at)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Vérifié
                                </span>
                                <small class="text-muted d-block mt-1">
                                    Le {{ $user->email_verified_at->format('d/m/Y à H:i') }}
                                </small>
                            @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-exclamation-circle me-1"></i>Non vérifié
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Membre depuis</label>
                        <div>
                            <strong>{{ $user->created_at->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-muted small">Dernière mise à jour</label>
                        <div>
                            <strong>{{ $user->updated_at->format('d/m/Y à H:i') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rôles -->
            <div class="card mb-4">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>Rôles assignés
                    </h5>
                </div>
                <div class="card-body">
                    @if ($roles->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($roles as $role)
                                <span class="badge bg-primary">
                                    <i class="bi bi-shield-check me-1"></i>{{ ucfirst(str_replace('_', ' ', $role)) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">
                            <i class="bi bi-info-circle me-2"></i>Aucun rôle assigné
                        </p>
                    @endif
                </div>
            </div>

            <!-- Permissions -->
            <div class="card">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-key me-2"></i>Permissions
                        <span class="badge bg-light text-dark ms-2">{{ $permissions->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if ($permissions->count() > 0)
                        <div class="small">
                            <div class="mb-2">
                                <strong>Total:</strong> {{ $permissions->count() }} permission(s)
                            </div>
                            <div class="accordion" id="permissionsAccordion">
                                @php
                                    $grouped = $permissions->groupBy(function($perm) {
                                        $parts = explode(' ', $perm);
                                        return $parts[0] ?? 'autre';
                                    });
                                @endphp
                                @foreach ($grouped as $module => $perms)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" aria-expanded="false">
                                                {{ ucfirst($module) }} <span class="badge bg-secondary ms-2">{{ $perms->count() }}</span>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse" data-bs-parent="#permissionsAccordion">
                                            <div class="accordion-body">
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($perms as $perm)
                                                        <li class="mb-1">
                                                            <i class="bi bi-check-circle text-success me-2"></i>
                                                            <small>{{ $perm }}</small>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">
                            <i class="bi bi-info-circle me-2"></i>Aucune permission assignée
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
