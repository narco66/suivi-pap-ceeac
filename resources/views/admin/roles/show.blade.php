<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-person-badge me-2"></i>Détails du rôle : {{ ucfirst(str_replace('_', ' ', $role->name)) }}
            </h2>
            <div>
                @can('update', $role)
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </a>
                @endcan
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0"><i class="bi bi-key me-2"></i>Permissions</h5>
                </div>
                <div class="card-body">
                    @if($role->permissions->count() > 0)
                        @foreach($permissions as $module => $modulePermissions)
                            @php
                                $rolePerms = $role->permissions->pluck('id')->toArray();
                                $modulePerms = $modulePermissions->whereIn('id', $rolePerms);
                            @endphp
                            @if($modulePerms->count() > 0)
                                <div class="mb-3">
                                    <h6>{{ ucfirst($module) }}</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($modulePerms as $permission)
                                            <span class="badge bg-primary">{{ $permission->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Aucune permission assignée</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Utilisateurs</h5>
                </div>
                <div class="card-body">
                    <p><strong>Total:</strong> {{ $role->users->count() }} utilisateur(s)</p>
                    @if($role->users->count() > 0)
                        <ul class="list-unstyled">
                            @foreach($role->users->take(10) as $user)
                                <li class="mb-1">
                                    <a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



