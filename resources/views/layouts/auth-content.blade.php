<div class="app-wrapper">
    <!-- Sidebar -->
    <x-app.sidebar />

    <!-- Main content area -->
    <div class="app-main">
        <!-- Topbar -->
        <x-app.topbar />

        <!-- Page Content -->
        <main class="app-content">
            <div class="container-fluid py-4">
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </div>
        </main>
    </div>
</div>

<!-- Sidebar JS -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if (sidebar && overlay) {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            document.body.classList.toggle('sidebar-open');
        }
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if (sidebar && overlay) {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.classList.remove('sidebar-open');
        }
    }

    // Fermer avec ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSidebar();
        }
    });

    // Fermer au clic sur overlay
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('sidebar-overlay');
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }
    });
</script>

