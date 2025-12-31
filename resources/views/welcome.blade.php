<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $config['hero']['title'] ?? 'SUIVI-PAPA CEEAC' }} - Système de Suivi des Plans d'Action Prioritaires</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- CEEAC CSS -->
    <link rel="stylesheet" href="{{ asset('css/ceeac.css') }}">
    
    <!-- Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    
            <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--ceeac-blue) 0%, var(--ceeac-blue-dark) 100%);
            min-height: calc(100vh - 76px);
            display: flex;
            align-items: center;
            padding: 3rem 0;
            margin-top: 76px;
        }
        
        .hero-content {
            color: white;
        }
        
        .logo-hero {
            width: 60px;
            height: 60px;
            margin-bottom: 1.5rem;
            filter: brightness(0) invert(1);
            object-fit: contain;
        }
        
        .feature-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .feature-icon-blue {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--ceeac-blue);
        }
        
        .feature-icon-green {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--ceeac-green);
        }
        
        .feature-icon-purple {
            background-color: rgba(124, 58, 237, 0.1);
            color: var(--ceeac-purple);
        }
        
        .feature-icon-yellow {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--ceeac-yellow);
        }
        
        .feature-icon-orange {
            background-color: rgba(249, 115, 22, 0.1);
            color: var(--ceeac-orange);
        }
        
        .feature-icon-red {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--ceeac-red);
        }
        
        .btn-ceeac-primary {
            background-color: white;
            color: var(--ceeac-blue);
            border: none;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        
        .btn-ceeac-primary:hover {
            background-color: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-ceeac-outline {
            background-color: transparent;
            color: white;
            border: 2px solid white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        
        .btn-ceeac-outline:hover {
            background-color: white;
            color: var(--ceeac-blue);
            transform: translateY(-2px);
        }
        
        .navbar-ceeac {
            background: linear-gradient(135deg, var(--ceeac-blue) 0%, var(--ceeac-blue-dark) 100%) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1030;
        }
        
        .navbar-ceeac .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .navbar-ceeac .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .navbar-ceeac .nav-link {
            color: white !important;
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1rem !important;
        }
        
        .navbar-ceeac .nav-link:hover,
        .navbar-ceeac .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.375rem;
        }
        
        .navbar-ceeac .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .navbar-ceeac .dropdown-item:hover {
            background-color: var(--ceeac-blue-lighter);
            color: var(--ceeac-blue);
        }
        
        @media (max-width: 991px) {
            .hero-section {
                margin-top: 56px;
                min-height: calc(100vh - 56px);
                padding: 2rem 0;
            }
            
            .logo-hero {
                width: 50px;
                height: 50px;
                margin-bottom: 1rem;
            }
            
            h1.display-5 {
                font-size: 2rem !important;
            }
        }
        
        .features-section {
            padding: 5rem 0;
            background-color: #f9fafb;
        }
        
        .modules-section {
            background-color: #f9fafb;
        }
        
        .faq-section {
            background-color: #f9fafb;
        }
        
        .accordion-button:not(.collapsed) {
            background-color: var(--ceeac-blue-lighter);
            color: var(--ceeac-blue);
        }
        
        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(30, 64, 175, 0.25);
        }
        
        .footer-ceeac {
            background: linear-gradient(135deg, var(--ceeac-blue-dark) 0%, var(--ceeac-blue) 100%);
            color: white;
            padding: 3rem 0 2rem;
        }
        
        .footer-ceeac a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .footer-ceeac a:hover {
            color: white;
        }
        
        .footer-ceeac h5, .footer-ceeac h6 {
            color: white;
            font-weight: 600;
        }
        
        .security-banner {
            background-color: #f9fafb;
        }
        
        .preview-section {
            background-color: #f9fafb;
        }
        
        .smooth-scroll {
            scroll-behavior: smooth;
        }
        
        /* Fix pour éviter les débordements */
        .container {
            max-width: 1200px;
        }
        
        /* Amélioration de la lisibilité */
        .hero-content h1 {
            line-height: 1.2;
        }
        
        .hero-content p.lead {
            font-size: 1.1rem;
        }
        
        .hero-content p.small {
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    @include('partials.landing.navbar')

    @include('partials.landing.sections.hero')
    @include('partials.landing.sections.security')
    @include('partials.landing.sections.features')
    @include('partials.landing.sections.modules')
    @include('partials.landing.sections.preview')
    @include('partials.landing.sections.faq')
    @include('partials.landing.sections.contact')

    @include('partials.landing.footer')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    </body>
</html>
