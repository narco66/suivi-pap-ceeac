@php
    // Fonctions helper pour la navigation (fallback si NavigationHelper n'est pas chargé)
    if (!function_exists('shouldShowItem')) {
        function shouldShowItem($item) {
            $user = auth()->user();
            if (!$user) return false;
            
            if (isset($item['permission']) && $item['permission']) {
                try {
                    if (!$user->can($item['permission'])) return false;
                } catch (\Exception $e) {
                    return false;
                }
            }
            
            // Vérification spéciale pour les routes admin.users
            if (isset($item['route']) && $item['route'] === 'admin.users.index') {
                try {
                    if (!$user->can('viewAny', \App\Models\User::class)) return false;
                } catch (\Exception $e) {
                    return false;
                }
            }
            
            if (isset($item['role']) && $item['role']) {
                $roles = is_array($item['role']) ? $item['role'] : [$item['role']];
                $hasRole = false;
                foreach ($roles as $role) {
                    if ($user->hasRole($role)) {
                        $hasRole = true;
                        break;
                    }
                }
                if (!$hasRole) return false;
            }
            
            if (isset($item['route']) && $item['route']) {
                if (!\Route::has($item['route'])) return false;
            }
            
            return true;
        }
        
        function isActive($item) {
            if (isset($item['active']) && is_array($item['active'])) {
                foreach ($item['active'] as $pattern) {
                    if (request()->routeIs($pattern)) return true;
                }
            }
            if (isset($item['route']) && $item['route']) {
                return request()->routeIs($item['route']);
            }
            if (isset($item['children']) && is_array($item['children'])) {
                foreach ($item['children'] as $child) {
                    if (isActive($child)) return true;
                }
            }
            return false;
        }
    }
    
    $navItems = config('navigation.items', []);
    $user = auth()->user();
@endphp

@if(auth()->check())
<!-- Sidebar -->
<aside id="sidebar" class="sidebar-ceeac" aria-label="Navigation principale">
    <!-- Logo et titre -->
    <div class="sidebar-header">
        <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" class="sidebar-brand d-flex align-items-center text-decoration-none">
            <img src="{{ asset('images/logo-ceeac.png') }}" alt="CEEAC" height="40" class="sidebar-logo me-2" onerror="this.style.display='none'">
            <div class="sidebar-brand-text">
                <div class="fw-bold text-white">SUIVI-PAPA</div>
                <small class="text-white-50">CEEAC</small>
            </div>
        </a>
        <button class="sidebar-close d-lg-none" type="button" aria-label="Fermer le menu" onclick="closeSidebar()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav" role="navigation">
        <ul class="sidebar-nav-list">
            @foreach($navItems as $item)
                @if(shouldShowItem($item))
                    @if(isset($item['children']) && is_array($item['children']))
                        <!-- Item avec sous-menu -->
                        @php
                            $isActive = isActive($item);
                            $hasVisibleChildren = false;
                            foreach ($item['children'] as $child) {
                                if (shouldShowItem($child)) {
                                    $hasVisibleChildren = true;
                                    break;
                                }
                            }
                        @endphp
                        @if($hasVisibleChildren)
                        <li class="sidebar-nav-item">
                            <button 
                                class="sidebar-nav-link {{ $isActive ? 'active' : '' }} collapsed" 
                                type="button"
                                data-bs-toggle="collapse" 
                                data-bs-target="#sidebar-submenu-{{ $loop->index }}"
                                aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                                aria-controls="sidebar-submenu-{{ $loop->index }}">
                                <i class="bi {{ $item['icon'] ?? 'bi-circle' }} sidebar-nav-icon"></i>
                                <span class="sidebar-nav-text">{{ $item['label'] }}</span>
                                <i class="bi bi-chevron-down sidebar-nav-chevron"></i>
                            </button>
                            <div class="collapse {{ $isActive ? 'show' : '' }}" id="sidebar-submenu-{{ $loop->index }}">
                                <ul class="sidebar-nav-sublist">
                                    @foreach($item['children'] as $child)
                                        @if(shouldShowItem($child))
                                            @php
                                                $childActive = isActive($child);
                                            @endphp
                                            <li class="sidebar-nav-subitem">
                                                <a 
                                                    href="{{ Route::has($child['route']) ? route($child['route']) : '#' }}" 
                                                    class="sidebar-nav-sublink {{ $childActive ? 'active' : '' }}"
                                                    @if($childActive) aria-current="page" @endif>
                                                    <i class="bi {{ $child['icon'] ?? 'bi-circle' }} sidebar-nav-icon"></i>
                                                    <span>{{ $child['label'] }}</span>
                                                    @if(isset($child['badge']))
                                                        <span class="sidebar-nav-badge">0</span>
                                                    @endif
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                        @endif
                    @else
                        <!-- Item simple -->
                        @php
                            $isActive = isActive($item);
                        @endphp
                        <li class="sidebar-nav-item">
                            <a 
                                href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                                class="sidebar-nav-link {{ $isActive ? 'active' : '' }}"
                                @if($isActive) aria-current="page" @endif>
                                <i class="bi {{ $item['icon'] ?? 'bi-circle' }} sidebar-nav-icon"></i>
                                <span class="sidebar-nav-text">{{ $item['label'] }}</span>
                                @if(isset($item['badge']))
                                    <span class="sidebar-nav-badge">0</span>
                                @endif
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </nav>

    <!-- Footer sidebar -->
    <div class="sidebar-footer">
        <small class="text-white-50">
            <i class="bi bi-shield-lock me-1"></i>
            Document interne – Ne pas diffuser sans autorisation
        </small>
    </div>
</aside>

<!-- Overlay mobile -->
<div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()" aria-hidden="true"></div>
@endif

