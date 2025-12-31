<nav class="navbar navbar-expand-lg navbar-ceeac" role="navigation" aria-label="Navigation principale" id="mainNavigation">
    <div class="container-fluid px-3 px-lg-4">
        <!-- Brand -->
        <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="{{ route(auth()->check() ? 'dashboard' : 'landing') }}" aria-label="Accueil SUIVI-PAPA CEEAC">
            <img src="{{ asset('images/logo-ceeac.png') }}" alt="CEEAC" height="32" class="me-2" style="max-width: 32px; object-fit: contain;" onerror="this.style.display='none'">
            <span class="d-none d-md-inline">SUIVI-PAPA CEEAC</span>
            <span class="d-md-none">SUIVI-PAPA</span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" 
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Basculer la navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Main Navigation -->
        @if(auth()->check())
        <div class="collapse navbar-collapse show" id="mainNavbar">
            <ul class="navbar-nav me-auto">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}"
                       aria-label="Tableau de bord">
                        <i class="bi bi-speedometer2 me-1"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                <!-- PAPA & Planification -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('papa.*') || request()->routeIs('gantt.*') ? 'active' : '' }}" 
                       href="#" 
                       id="papaMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true"
                       aria-label="Menu PAPA et Planification">
                        <i class="bi bi-folder2-open me-1"></i>
                        <span>PAPA</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="papaMenu">
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-folder me-2"></i>Gestion
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('papa.index') ? 'active' : '' }}" href="{{ route('papa.index') }}">
                                <i class="bi bi-list-ul me-2 text-primary"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Liste des PAPA</div>
                                    <small class="text-muted d-block">Consulter tous les plans</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('papa.create') ? 'active' : '' }}" href="{{ route('papa.create') }}">
                                <i class="bi bi-plus-circle me-2 text-success"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Créer un PAPA</div>
                                    <small class="text-muted d-block">Nouveau plan d'action</small>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-diagram-3 me-2"></i>Visualisation
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('gantt.*') ? 'active' : '' }}" href="{{ route('gantt.index') }}">
                                <i class="bi bi-diagram-3 me-2 text-info"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Diagramme de Gantt</div>
                                    <small class="text-muted d-block">Vue temporelle des tâches</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Suivi & Exécution -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('objectifs.*') || request()->routeIs('actions-prioritaires.*') || request()->routeIs('taches.*') || request()->routeIs('avancements.*') ? 'active' : '' }}" 
                       href="#" 
                       id="suiviMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true"
                       aria-label="Menu Suivi et Exécution">
                        <i class="bi bi-bullseye me-1"></i>
                        <span>Suivi</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="suiviMenu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('objectifs.*') ? 'active' : '' }}" href="{{ route('objectifs.index') }}">
                                <i class="bi bi-bullseye me-2 text-primary"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Objectifs</div>
                                    <small class="text-muted d-block">Objectifs stratégiques</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('actions-prioritaires.*') ? 'active' : '' }}" href="{{ route('actions-prioritaires.index') }}">
                                <i class="bi bi-lightning-charge me-2 text-warning"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Actions prioritaires</div>
                                    <small class="text-muted d-block">Actions à réaliser</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('taches.*') ? 'active' : '' }}" href="{{ route('taches.index') }}">
                                <i class="bi bi-list-check me-2 text-info"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Tâches</div>
                                    <small class="text-muted d-block">Gestion des tâches</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('avancements.*') ? 'active' : '' }}" href="{{ route('avancements.index') }}">
                                <i class="bi bi-graph-up-arrow me-2 text-success"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Avancements</div>
                                    <small class="text-muted d-block">Suivi de progression</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Analyse & Reporting -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('kpi.*') || request()->routeIs('alertes.*') ? 'active' : '' }}" 
                       href="#" 
                       id="analyseMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true"
                       aria-label="Menu Analyse et Reporting">
                        <i class="bi bi-graph-up me-1"></i>
                        <span>Analyse</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="analyseMenu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('kpi.*') ? 'active' : '' }}" href="{{ route('kpi.index') }}">
                                <i class="bi bi-graph-up-arrow me-2 text-primary"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Indicateurs KPI</div>
                                    <small class="text-muted d-block">Indicateurs de performance</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('alertes.*') ? 'active' : '' }}" href="{{ route('alertes.index') }}">
                                <i class="bi bi-bell me-2 text-danger"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Alertes</div>
                                    <small class="text-muted d-block">Notifications et alertes</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Référentiels -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('commissaires.*') || request()->routeIs('commissions.*') || request()->routeIs('departements.*') || request()->routeIs('directions-*') ? 'active' : '' }}" 
                       href="#" 
                       id="referentielMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true"
                       aria-label="Menu Référentiels">
                        <i class="bi bi-book me-1"></i>
                        <span>Référentiels</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="referentielMenu">
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-people me-2"></i>Organisation
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('commissaires.*') ? 'active' : '' }}" href="{{ route('commissaires.index') }}">
                                <i class="bi bi-person-badge me-2"></i>Commissaires
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('commissions.*') ? 'active' : '' }}" href="{{ route('commissions.index') }}">
                                <i class="bi bi-people me-2"></i>Commissions
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-building me-2"></i>Structures
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('departements.*') ? 'active' : '' }}" href="{{ route('departements.index') }}">
                                <i class="bi bi-building me-2"></i>Départements
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('directions-appui.*') ? 'active' : '' }}" href="{{ route('directions-appui.index') }}">
                                <i class="bi bi-briefcase me-2"></i>Directions d'Appui
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('directions-techniques.*') ? 'active' : '' }}" href="{{ route('directions-techniques.index') }}">
                                <i class="bi bi-gear me-2"></i>Directions Techniques
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Import/Export -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('import.*') || request()->routeIs('export.*') ? 'active' : '' }}" 
                       href="#" 
                       id="importExportMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true"
                       aria-label="Menu Import et Export">
                        <i class="bi bi-arrow-left-right me-1"></i>
                        <span>Données</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="importExportMenu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('import.*') ? 'active' : '' }}" href="{{ route('import.index') }}">
                                <i class="bi bi-upload me-2 text-success"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Importer</div>
                                    <small class="text-muted d-block">Import de données Excel</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('export.*') ? 'active' : '' }}" href="{{ route('export.index') }}">
                                <i class="bi bi-download me-2 text-primary"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Exporter</div>
                                    <small class="text-muted d-block">Export de données</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Administration (conditionnel) -->
                @if(auth()->check() && (auth()->user()->hasRole('admin_dsi') || auth()->user()->hasRole('admin') || auth()->user()->can('admin.access')))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.*') ? 'active' : '' }}" 
                       href="#" 
                       id="adminMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true"
                       aria-label="Menu Administration">
                        <i class="bi bi-shield-lock me-1"></i>
                        <span>Administration</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="adminMenu">
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-people me-2"></i>Gestion des accès
                            </h6>
                        </li>
                        @can('viewAny', \App\Models\User::class)
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-2"></i>Utilisateurs
                            </a>
                        </li>
                        @endcan
                        @can('viewAny', \Spatie\Permission\Models\Role::class)
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                <i class="bi bi-person-badge me-2"></i>Rôles & Permissions
                            </a>
                        </li>
                        @endcan
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-sliders me-2"></i>Configuration
                            </h6>
                        </li>
                        @can('viewAny', \App\Models\Structure::class)
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.structures.*') ? 'active' : '' }}" href="{{ route('admin.structures.index') }}">
                                <i class="bi bi-building me-2"></i>Structures
                            </a>
                        </li>
                        @endcan
                        @can('viewAny', \App\Models\Setting::class)
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-sliders me-2"></i>Paramètres
                            </a>
                        </li>
                        @endcan
                        @can('viewAny', \App\Models\Ressource::class)
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.ressources.*') ? 'active' : '' }}" href="{{ route('admin.ressources.index') }}">
                                <i class="bi bi-folder me-2"></i>Ressources
                            </a>
                        </li>
                        @endcan
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-shield-check me-2"></i>Audit & Monitoring
                            </h6>
                        </li>
                        @can('viewAny', \App\Models\AuditLog::class)
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}" href="{{ route('admin.audit.index') }}">
                                <i class="bi bi-journal-text me-2"></i>Journal d'Audit
                            </a>
                        </li>
                        @endcan
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.system.*') ? 'active' : '' }}" href="{{ route('admin.system.health') }}">
                                <i class="bi bi-heart-pulse me-2"></i>Santé Système
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>

            <!-- User Menu -->
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" 
                       href="#" 
                       id="userMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true"
                       aria-label="Menu utilisateur">
                        <i class="bi bi-person-circle me-2 fs-5"></i>
                        <span class="d-none d-lg-inline">{{ \Illuminate\Support\Str::limit(auth()->user()->name ?? 'Utilisateur', 20) }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userMenu">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-semibold">{{ auth()->user()->name ?? 'Utilisateur' }}</div>
                            <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person me-2"></i>Mon profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('ressources') }}">
                                <i class="bi bi-folder me-2"></i>Ressources
                            </a>
                        </li>
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
                </li>
            </ul>
        </div>
        @else
        <!-- Guest Navigation -->
        <div class="collapse navbar-collapse" id="mainNavbar">
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
                <li class="nav-item ms-2">
                    <a href="{{ route('login') }}" class="btn btn-light btn-sm btn-ceeac">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
                    </a>
                </li>
            </ul>
        </div>
        @endauth
    </div>
</nav>
