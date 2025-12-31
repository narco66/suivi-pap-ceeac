@php
    $user = auth()->user();
@endphp

@if(auth()->check())
<!-- Topbar -->
<header class="topbar-ceeac" role="banner">
    <div class="topbar-container">
        <!-- Bouton menu mobile -->
        <button 
            class="topbar-menu-toggle d-lg-none" 
            type="button" 
            aria-label="Ouvrir le menu"
            onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>

        <!-- Titre de la page (optionnel) -->
        <div class="topbar-title d-none d-md-flex align-items-center">
            @hasSection('header')
                @yield('header')
            @elseif(isset($header))
                {{ $header }}
            @else
                <h1 class="h5 mb-0 fw-semibold text-dark">{{ config('app.name', 'SUIVI-PAPA CEEAC') }}</h1>
            @endif
        </div>

        <!-- Actions droite -->
        <div class="topbar-actions ms-auto d-flex align-items-center gap-3">
            <!-- Notifications (optionnel) -->
            <div class="topbar-notifications">
                <button class="btn btn-link text-dark p-2 position-relative" type="button" aria-label="Notifications">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                        0
                    </span>
                </button>
            </div>

            <!-- Menu utilisateur -->
            <div class="dropdown">
                <button 
                    class="btn btn-link text-dark p-0 d-flex align-items-center gap-2 text-decoration-none" 
                    type="button" 
                    id="userMenuDropdown"
                    data-bs-toggle="dropdown" 
                    aria-expanded="false">
                    <div class="topbar-user-avatar">
                        <i class="bi bi-person-circle fs-4"></i>
                    </div>
                    <div class="d-none d-md-block text-start">
                        <div class="fw-semibold small">{{ $user->name ?? 'Utilisateur' }}</div>
                        <small class="text-muted">{{ $user->email ?? '' }}</small>
                    </div>
                    <i class="bi bi-chevron-down d-none d-md-block"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userMenuDropdown">
                    <li class="px-3 py-2 border-bottom">
                        <div class="fw-semibold">{{ $user->name ?? 'Utilisateur' }}</div>
                        <small class="text-muted">{{ $user->email ?? '' }}</small>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @if(Route::has('profile.edit'))
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2"></i>Mon profil
                        </a>
                    </li>
                    @endif
                    @if(Route::has('ressources'))
                    <li>
                        <a class="dropdown-item" href="{{ route('ressources') }}">
                            <i class="bi bi-folder me-2"></i>Ressources
                        </a>
                    </li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
@endif

