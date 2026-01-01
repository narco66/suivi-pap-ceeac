<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-person-badge-plus me-2"></i>Créer un rôle
            </h2>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nom du rôle <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Utilisez des underscores pour les espaces (ex: direction_manager)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Permissions</label>
                    @foreach($permissions as $module => $modulePermissions)
                        <div class="card mb-3">
                            <div class="card-header">
                                <strong>{{ ucfirst($module) }}</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($modulePermissions as $permission)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="permissions[]" value="{{ $permission->id }}" 
                                                       id="perm_{{ $permission->id }}"
                                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>
                    <button type="submit" class="btn btn-ceeac-primary">
                        <i class="bi bi-save me-2"></i>Créer le rôle
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>



