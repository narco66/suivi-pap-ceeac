<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="row g-3">
        <div class="col-md-12">
            <label for="update_password_current_password" class="form-label">
                <i class="bi bi-lock me-1"></i>Mot de passe actuel
            </label>
            <input 
                type="password" 
                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                id="update_password_current_password" 
                name="current_password" 
                autocomplete="current-password"
            >
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="update_password_password" class="form-label">
                <i class="bi bi-key me-1"></i>Nouveau mot de passe
            </label>
            <input 
                type="password" 
                class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                id="update_password_password" 
                name="password" 
                autocomplete="new-password"
            >
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Utilisez un mot de passe long et aléatoire pour garantir la sécurité de votre compte.
            </small>
        </div>

        <div class="col-md-12">
            <label for="update_password_password_confirmation" class="form-label">
                <i class="bi bi-key-fill me-1"></i>Confirmer le nouveau mot de passe
            </label>
            <input 
                type="password" 
                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                autocomplete="new-password"
            >
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-ceeac-primary">
                <i class="bi bi-save me-2"></i>Mettre à jour le mot de passe
            </button>
        </div>
    </div>
</form>
