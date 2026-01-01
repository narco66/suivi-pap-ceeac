<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-building me-2"></i>Détails de la structure : {{ $structure->name }}
            </h2>
            <div>
                @can('update', $structure)
                    <a href="{{ route('admin.structures.edit', $structure) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </a>
                @endcan
                <a href="{{ route('admin.structures.index') }}" class="btn btn-outline-secondary">
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
                            <label class="form-label text-muted small">Code</label>
                            <div><code>{{ $structure->code }}</code></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Nom</label>
                            <div><strong>{{ $structure->name }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Type</label>
                            <div><span class="badge bg-secondary">{{ $structure->type }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Structure parente</label>
                            <div>{{ $structure->parent?->name ?? '-' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small">Description</label>
                            <div>{{ $structure->description ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Email</label>
                            <div>{{ $structure->email ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Téléphone</label>
                            <div>{{ $structure->phone ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Statut</label>
                            <div>
                                @if($structure->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
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
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Utilisateurs</h5>
                </div>
                <div class="card-body">
                    <p><strong>Total:</strong> {{ $structure->users->count() }} utilisateur(s)</p>
                    @if($structure->users->count() > 0)
                        <ul class="list-unstyled">
                            @foreach($structure->users->take(10) as $user)
                                <li class="mb-1">
                                    <a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            @if($structure->children->count() > 0)
                <div class="card">
                    <div class="card-header bg-ceeac-blue text-white">
                        <h5 class="mb-0"><i class="bi bi-diagram-3 me-2"></i>Structures enfants</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            @foreach($structure->children as $child)
                                <li class="mb-1">
                                    <a href="{{ route('admin.structures.show', $child) }}">{{ $child->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>



