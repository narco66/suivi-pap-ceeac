<nav class="navbar navbar-expand-lg navbar-ceeac fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="{{ route('landing') }}">
            <img src="{{ asset('images/logo-ceeac.png') }}" alt="CEEAC" height="32" class="me-2" style="max-width: 32px; object-fit: contain;" onerror="this.style.display='none'">
            <span class="d-none d-sm-inline">SUIVI-PAPA CEEAC</span>
            <span class="d-sm-none">SUIVI-PAPA</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('landing') ? 'active' : '' }}" href="{{ route('landing') }}">
                        <i class="bi bi-house me-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('ressources.*') ? 'active' : '' }}" href="{{ route('ressources') }}">
                        <i class="bi bi-folder me-1"></i>Ressources
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('docs') ? 'active' : '' }}" href="{{ route('docs') }}">
                        <i class="bi bi-book me-1"></i>Documentation
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name ?? 'Utilisateur' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2"></i>Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}" class="btn btn-light btn-sm btn-ceeac">
                            <i class="bi bi-shield-lock me-1"></i>Accès sécurisé
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

