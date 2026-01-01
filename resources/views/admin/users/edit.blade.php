<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-pencil me-2"></i>Modifier l'utilisateur : {{ $user->name }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password">
                        <small class="form-text text-muted">Laisser vide pour ne pas modifier</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="col-md-4">
                        <label for="matricule" class="form-label">Matricule</label>
                        <input type="text" class="form-control @error('matricule') is-invalid @enderror" 
                               id="matricule" name="matricule" value="{{ old('matricule', $user->matricule) }}">
                        @error('matricule')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control @error('telephone') is-invalid @enderror" 
                               id="telephone" name="telephone" value="{{ old('telephone', $user->telephone ?? $user->phone) }}">
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="fonction" class="form-label">Fonction</label>
                        <input type="text" class="form-control @error('fonction') is-invalid @enderror" 
                               id="fonction" name="fonction" value="{{ old('fonction', $user->fonction ?? $user->title) }}">
                        @error('fonction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="structure_id" class="form-label">Structure</label>
                        <select class="form-select @error('structure_id') is-invalid @enderror" 
                                id="structure_id" name="structure_id">
                            <option value="">Sélectionner une structure</option>
                            @foreach($structures as $structure)
                                <option value="{{ $structure->id }}" {{ old('structure_id', $user->structure_id) == $structure->id ? 'selected' : '' }}>
                                    {{ $structure->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('structure_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status">
                            @php
                                $currentStatus = $user->status ?? ($user->actif ? 'actif' : 'inactif');
                            @endphp
                            <option value="actif" {{ old('status', $currentStatus) === 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="suspendu" {{ old('status', $currentStatus) === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="inactif" {{ old('status', $currentStatus) === 'inactif' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Rôles</label>
                        <div class="row">
                            @foreach($roles as $role)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="roles[]" value="{{ $role->id }}" 
                                               id="role_{{ $role->id }}"
                                               {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('roles.*')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-ceeac-primary">
                            <i class="bi bi-save me-2"></i>Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>



