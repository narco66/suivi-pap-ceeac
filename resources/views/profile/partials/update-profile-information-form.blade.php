<form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="row g-3">
        <div class="col-md-12">
            <label for="name" class="form-label">
                <i class="bi bi-person me-1"></i>Nom complet
            </label>
            <input 
                type="text" 
                class="form-control @error('name') is-invalid @enderror" 
                id="name" 
                name="name" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name"
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="email" class="form-label">
                <i class="bi bi-envelope me-1"></i>Adresse email
            </label>
            <input 
                type="email" 
                class="form-control @error('email') is-invalid @enderror" 
                id="email" 
                name="email" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="username"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <div class="alert alert-warning mb-2">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Votre adresse email n'est pas vérifiée.</strong>
                    </div>
                    <button type="submit" form="send-verification" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-send me-1"></i>Renvoyer l'email de vérification
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2 mb-0">
                            <i class="bi bi-check-circle me-2"></i>
                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-ceeac-primary">
                <i class="bi bi-save me-2"></i>Enregistrer les modifications
            </button>
        </div>
    </div>
</form>
