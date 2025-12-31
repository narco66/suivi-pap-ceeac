<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-person-badge me-2"></i>Gestion des Rôles & Permissions
            </h2>
            @can('create', \Spatie\Permission\Models\Role::class)
                <a href="{{ route('admin.roles.create') }}" class="btn btn-ceeac-primary">
                    <i class="bi bi-plus-circle me-2"></i>Nouveau rôle
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body">
            @if($roles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Permissions</th>
                                <th>Utilisateurs</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <strong>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $role->permissions->count() }} permission(s)</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $role->users->count() }} utilisateur(s)</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view', $role)
                                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @can('update', $role)
                                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary">
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
                {{ $roles->links() }}
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>Aucun rôle trouvé.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>


