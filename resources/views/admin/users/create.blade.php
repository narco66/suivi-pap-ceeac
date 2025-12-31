<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-person-plus me-2"></i>Créer un utilisateur
            </h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
            </a>
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

    <div class="card card-ceeac">
        <div class="card-header bg-ceeac-gradient text-white">
            <h5 class="mb-0">
                <i class="bi bi-person-plus me-2"></i>Informations de l'utilisateur
            </h5>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <h6 class="alert-heading">
                        <i class="bi bi-exclamation-triangle me-2"></i>Erreurs de validation
                    </h6>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">
                            <i class="bi bi-person me-1"></i>Nom complet <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-ceeac @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required 
                               placeholder="Entrez le nom complet">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">
                            <i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control form-control-ceeac @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required 
                               placeholder="exemple@ceeac.org">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold">
                            <i class="bi bi-lock me-1"></i>Mot de passe <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control form-control-ceeac @error('password') is-invalid @enderror" 
                               id="password" name="password" required 
                               placeholder="Minimum 8 caractères">
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Le mot de passe doit contenir au moins 8 caractères.
                        </small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold">
                            <i class="bi bi-lock-fill me-1"></i>Confirmer le mot de passe <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control form-control-ceeac" 
                               id="password_confirmation" name="password_confirmation" required 
                               placeholder="Répétez le mot de passe">
                    </div>

                    <div class="col-md-4">
                        <label for="matricule" class="form-label fw-semibold">
                            <i class="bi bi-card-text me-1"></i>Matricule
                        </label>
                        <input type="text" class="form-control form-control-ceeac @error('matricule') is-invalid @enderror" 
                               id="matricule" name="matricule" value="{{ old('matricule') }}" 
                               placeholder="Matricule de l'utilisateur">
                        @error('matricule')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="telephone" class="form-label fw-semibold">
                            <i class="bi bi-telephone me-1"></i>Téléphone
                        </label>
                        <input type="text" class="form-control form-control-ceeac @error('telephone') is-invalid @enderror" 
                               id="telephone" name="telephone" value="{{ old('telephone') }}" 
                               placeholder="+XXX XX XX XX XX">
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="fonction" class="form-label fw-semibold">
                            <i class="bi bi-briefcase me-1"></i>Fonction
                        </label>
                        <input type="text" class="form-control form-control-ceeac @error('fonction') is-invalid @enderror" 
                               id="fonction" name="fonction" value="{{ old('fonction') }}" 
                               placeholder="Fonction de l'utilisateur">
                        @error('fonction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="structure_id" class="form-label fw-semibold">
                            <i class="bi bi-building me-1"></i>Structure
                        </label>
                        <select class="form-select form-control-ceeac @error('structure_id') is-invalid @enderror" 
                                id="structure_id" name="structure_id">
                            <option value="">Sélectionner une structure</option>
                            @foreach($structures as $structure)
                                <option value="{{ $structure->id }}" {{ old('structure_id') == $structure->id ? 'selected' : '' }}>
                                    {{ $structure->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('structure_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">
                            <i class="bi bi-info-circle me-1"></i>Statut
                        </label>
                        <select class="form-select form-control-ceeac @error('status') is-invalid @enderror" 
                                id="status" name="status">
                            <option value="actif" {{ old('status', 'actif') === 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="suspendu" {{ old('status') === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="inactif" {{ old('status') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person-badge me-1"></i>Rôles
                        </label>
                        <div class="card card-ceeac bg-light">
                            <div class="card-body">
                                <div class="row">
                                    @foreach($roles as $role)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="roles[]" value="{{ $role->id }}" 
                                                       id="role_{{ $role->id }}"
                                                       {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                                    <i class="bi bi-shield-check me-1"></i>{{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('roles.*')
                            <div class="text-danger small mt-2">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-12 mt-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-ceeac">
                                <i class="bi bi-save me-2"></i>Créer l'utilisateur
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


