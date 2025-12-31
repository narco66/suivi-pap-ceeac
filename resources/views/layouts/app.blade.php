<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SUIVI-PAPA CEEAC') }} - Dashboard</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
    <!-- CEEAC CSS -->
    <link rel="stylesheet" href="{{ asset('css/ceeac.css') }}">
    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Stack pour les styles additionnels -->
        @stack('styles')
        
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f9fafb;
                margin: 0;
                padding: 0;
            }
            .app-wrapper {
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        @if(auth()->check())
            <!-- Utiliser le layout avec sidebar pour les utilisateurs authentifiés -->
            <!-- Le contenu est géré par auth-content.blade.php -->
            @include('layouts.auth-content')
        @else
            <!-- Menu navigation classique pour les invités -->
            <div class="min-h-screen bg-gray-100" style="margin: 0; padding: 0;">
                <x-app.navigation />

                <!-- Page Heading -->
                @hasSection('header')
                    <header class="bg-white shadow-sm border-bottom">
                        <div class="container-fluid py-3 px-4">
                            @yield('header')
                        </div>
                    </header>
                @elseif(isset($header))
                    <header class="bg-white shadow-sm border-bottom">
                        <div class="container-fluid py-3 px-4">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="py-4">
                    <div class="container-fluid">
                        @hasSection('content')
                            @yield('content')
                        @else
                            {{ $slot ?? '' }}
                        @endif
                    </div>
                </main>
            </div>
        @endif
        
        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        
        <!-- Script de vérification du menu -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const nav = document.getElementById('mainNavigation');
                const collapse = document.getElementById('mainNavbar');
                
                if (nav) {
                    console.log('Menu trouvé:', nav);
                    nav.style.display = 'block';
                    nav.style.visibility = 'visible';
                    nav.style.opacity = '1';
                } else {
                    console.error('Menu introuvable!');
                }
                
                if (collapse) {
                    console.log('Collapse trouvé:', collapse);
                    // Forcer l'affichage sur desktop
                    if (window.innerWidth >= 992) {
                        collapse.classList.add('show');
                        collapse.style.display = 'flex';
                    }
                } else {
                    console.error('Collapse introuvable!');
                }
            });
        </script>
        
        <!-- Stack pour les scripts additionnels -->
        @stack('scripts')
    </body>
</html>
