@php
    $user = auth()->user();
    $isAuthenticated = auth()->check();
    $hasAdminAccess = $isAuthenticated && (
        $user->hasRole('admin_dsi') || 
        $user->hasRole('admin') || 
        $user->can('admin.access')
    );
@endphp

@if($isAuthenticated)
<nav class="navbar navbar-expand-lg navbar-ceeac" role="navigation" aria-label="Navigation principale" id="mainNavigation">
    <div class="container-fluid px-3 px-lg-4">
        <!-- Brand -->
        <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" aria-label="Accueil SUIVI-PAPA CEEAC">
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
        <div class="collapse navbar-collapse show" id="mainNavbar">
            <ul class="navbar-nav me-auto">
                <!-- Dashboard -->
                @if(Route::has('dashboard'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}"
                       aria-label="Tableau de bord">
                        <i class="bi bi-speedometer2 me-1"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                @endif

                <!-- PAPA & Planification -->
                @if(Route::has('papa.index') || Route::has('gantt.index'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('papa.*') || request()->routeIs('gantt.*') ? 'active' : '' }}" 
                       href="#" 
                       id="papaMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true">
                        <i class="bi bi-folder2-open me-1"></i>
                        <span>PAPA</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="papaMenu">
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-folder me-2"></i>Gestion
                            </h6>
                        </li>
                        @if(Route::has('papa.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('papa.index') ? 'active' : '' }}" href="{{ route('papa.index') }}">
                                <i class="bi bi-list-ul me-2 text-primary"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Liste des PAPA</div>
                                    <small class="text-muted d-block">Consulter tous les plans</small>
                                </div>
                            </a>
                        </li>
                        @endif
                        @if(Route::has('papa.create'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('papa.create') ? 'active' : '' }}" href="{{ route('papa.create') }}">
                                <i class="bi bi-plus-circle me-2 text-success"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Créer un PAPA</div>
                                    <small class="text-muted d-block">Nouveau plan d'action</small>
                                </div>
                            </a>
                        </li>
                        @endif
                        @if(Route::has('gantt.index'))
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
                        @endif
                    </ul>
                </li>
                @endif

                <!-- Suivi & Exécution -->
                @if(Route::has('objectifs.index') || Route::has('actions-prioritaires.index') || Route::has('taches.index') || Route::has('avancements.index'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('objectifs.*') || request()->routeIs('actions-prioritaires.*') || request()->routeIs('taches.*') || request()->routeIs('avancements.*') ? 'active' : '' }}" 
                       href="#" 
                       id="suiviMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true">
                        <i class="bi bi-bullseye me-1"></i>
                        <span>Suivi</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="suiviMenu">
                        @if(Route::has('objectifs.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('objectifs.*') ? 'active' : '' }}" href="{{ route('objectifs.index') }}">
                                <i class="bi bi-bullseye me-2 text-primary"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Objectifs</div>
                                    <small class="text-muted d-block">Objectifs stratégiques</small>
                                </div>
                            </a>
                        </li>
                        @endif
                        @if(Route::has('actions-prioritaires.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('actions-prioritaires.*') ? 'active' : '' }}" href="{{ route('actions-prioritaires.index') }}">
                                <i class="bi bi-lightning-charge me-2 text-warning"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Actions prioritaires</div>
                                    <small class="text-muted d-block">Actions à réaliser</small>
                                </div>
                            </a>
                        </li>
                        @endif
                        @if(Route::has('taches.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('taches.*') ? 'active' : '' }}" href="{{ route('taches.index') }}">
                                <i class="bi bi-list-check me-2 text-info"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Tâches</div>
                                    <small class="text-muted d-block">Gestion des tâches</small>
                                </div>
                            </a>
                        </li>
                        @endif
                        @if(Route::has('avancements.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('avancements.*') ? 'active' : '' }}" href="{{ route('avancements.index') }}">
                                <i class="bi bi-graph-up-arrow me-2 text-success"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Avancements</div>
                                    <small class="text-muted d-block">Suivi de progression</small>
                                </div>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                <!-- Analyse & Reporting -->
                @if(Route::has('kpi.index') || Route::has('alertes.index'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('kpi.*') || request()->routeIs('alertes.*') ? 'active' : '' }}" 
                       href="#" 
                       id="analyseMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true">
                        <i class="bi bi-graph-up me-1"></i>
                        <span>Analyse</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="analyseMenu">
                        @if(Route::has('kpi.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('kpi.*') ? 'active' : '' }}" href="{{ route('kpi.index') }}">
                                <i class="bi bi-graph-up-arrow me-2 text-primary"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Indicateurs KPI</div>
                                    <small class="text-muted d-block">Indicateurs de performance</small>
                                </div>
                            </a>
                        </li>
                        @endif
                        @if(Route::has('alertes.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('alertes.*') ? 'active' : '' }}" href="{{ route('alertes.index') }}">
                                <i class="bi bi-bell me-2 text-danger"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Alertes</div>
                                    <small class="text-muted d-block">Notifications et alertes</small>
                                </div>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                <!-- Référentiels -->
                @if(Route::has('commissaires.index') || Route::has('commissions.index') || Route::has('departements.index') || Route::has('directions-appui.index') || Route::has('directions-techniques.index'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('commissaires.*') || request()->routeIs('commissions.*') || request()->routeIs('departements.*') || request()->routeIs('directions-*') ? 'active' : '' }}" 
                       href="#" 
                       id="referentielMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true">
                        <i class="bi bi-book me-1"></i>
                        <span>Référentiels</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="referentielMenu">
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-people me-2"></i>Organisation
                            </h6>
                        </li>
                        @if(Route::has('commissaires.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('commissaires.*') ? 'active' : '' }}" href="{{ route('commissaires.index') }}">
                                <i class="bi bi-person-badge me-2"></i>Commissaires
                            </a>
                        </li>
                        @endif
                        @if(Route::has('commissions.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('commissions.*') ? 'active' : '' }}" href="{{ route('commissions.index') }}">
                                <i class="bi bi-people me-2"></i>Commissions
                            </a>
                        </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-building me-2"></i>Structures
                            </h6>
                        </li>
                        @if(Route::has('departements.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('departements.*') ? 'active' : '' }}" href="{{ route('departements.index') }}">
                                <i class="bi bi-building me-2"></i>Départements
                            </a>
                        </li>
                        @endif
                        @if(Route::has('directions-appui.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('directions-appui.*') ? 'active' : '' }}" href="{{ route('directions-appui.index') }}">
                                <i class="bi bi-briefcase me-2"></i>Directions d'Appui
                            </a>
                        </li>
                        @endif
                        @if(Route::has('directions-techniques.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('directions-techniques.*') ? 'active' : '' }}" href="{{ route('directions-techniques.index') }}">
                                <i class="bi bi-gear me-2"></i>Directions Techniques
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                <!-- Import/Export -->
                @if(Route::has('import.index') || Route::has('export.index'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('import.*') || request()->routeIs('export.*') ? 'active' : '' }}" 
                       href="#" 
                       id="importExportMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true">
                        <i class="bi bi-arrow-left-right me-1"></i>
                        <span>Données</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="importExportMenu">
                        @if(Route::has('import.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('import.*') ? 'active' : '' }}" href="{{ route('import.index') }}">
                                <i class="bi bi-upload me-2 text-success"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Importer</div>
                                    <small class="text-muted d-block">Import de données Excel</small>
                                </div>
                            </a>
                        </li>
                        @endif
                        @if(Route::has('export.index'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('export.*') ? 'active' : '' }}" href="{{ route('export.index') }}">
                                <i class="bi bi-download me-2 text-primary"></i>
                                <div class="d-inline-block">
                                    <div class="fw-semibold">Exporter</div>
                                    <small class="text-muted d-block">Export de données</small>
                                </div>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                <!-- Administration (conditionnel) -->
                @if($hasAdminAccess)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.*') ? 'active' : '' }}" 
                       href="#" 
                       id="adminMenu" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       aria-haspopup="true">
                        <i class="bi bi-shield-lock me-1"></i>
                        <span>Administration</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-start shadow-lg" aria-labelledby="adminMenu">
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-people me-2"></i>Gestion des accès
                            </h6>
                        </li>
                        @if(Route::has('admin.users.index') && $user->can('viewAny', \App\Models\User::class))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-2"></i>Utilisateurs
                            </a>
                        </li>
                        @endif
                        @if(Route::has('admin.roles.index') && $user->can('viewAny', \Spatie\Permission\Models\Role::class))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                <i class="bi bi-person-badge me-2"></i>Rôles & Permissions
                            </a>
                        </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-sliders me-2"></i>Configuration
                            </h6>
                        </li>
                        @if(Route::has('admin.structures.index') && $user->can('viewAny', \App\Models\Structure::class))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.structures.*') ? 'active' : '' }}" href="{{ route('admin.structures.index') }}">
                                <i class="bi bi-building me-2"></i>Structures
                            </a>
                        </li>
                        @endif
                        @if(Route::has('admin.settings.index') && $user->can('viewAny', \App\Models\Setting::class))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-sliders me-2"></i>Paramètres
                            </a>
                        </li>
                        @endif
                        @if(Route::has('admin.ressources.index') && $user->can('viewAny', \App\Models\Ressource::class))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.ressources.*') ? 'active' : '' }}" href="{{ route('admin.ressources.index') }}">
                                <i class="bi bi-folder me-2"></i>Ressources
                            </a>
                        </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted">
                                <i class="bi bi-shield-check me-2"></i>Audit & Monitoring
                            </h6>
                        </li>
                        @if(Route::has('admin.audit.index') && $user->can('viewAny', \App\Models\AuditLog::class))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}" href="{{ route('admin.audit.index') }}">
                                <i class="bi bi-journal-text me-2"></i>Journal d'Audit
                            </a>
                        </li>
                        @endif
                        @if(Route::has('admin.system.health'))
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.system.*') ? 'active' : '' }}" href="{{ route('admin.system.health') }}">
                                <i class="bi bi-heart-pulse me-2"></i>Santé Système
                            </a>
                        </li>
                        @endif
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
                       aria-haspopup="true">
                        <i class="bi bi-person-circle me-2 fs-5"></i>
                        <span class="d-none d-lg-inline">{{ \Illuminate\Support\Str::limit($user->name ?? 'Utilisateur', 20) }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userMenu">
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
                            <form method="POST" action="{{ route('logout') }}">
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
    </div>
</nav>
@endif

