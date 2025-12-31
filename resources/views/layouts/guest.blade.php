<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SUIVI-PAPA CEEAC') }} - Connexion</title>

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
                background: linear-gradient(135deg, var(--ceeac-blue) 0%, var(--ceeac-blue-dark) 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
            }
            
            .auth-container {
                background: white;
                border-radius: 1rem;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                overflow: hidden;
                max-width: 450px;
                width: 100%;
            }
            
            .auth-header {
                background: linear-gradient(135deg, var(--ceeac-blue) 0%, var(--ceeac-blue-dark) 100%);
                color: white;
                padding: 2rem;
                text-align: center;
            }
            
            .auth-body {
                padding: 2rem;
            }
            
            .logo-container {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-bottom: 1rem;
            }
            
            .logo-container svg {
                width: 48px;
                height: 48px;
                fill: white;
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-header">
                <div class="logo-container">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                    </svg>
                </div>
                <h3 class="mb-0 fw-bold">SUIVI-PAPA CEEAC</h3>
                <p class="mb-0 mt-2 opacity-75">Système de Suivi des Plans d'Action Prioritaires</p>
            </div>
            
            <div class="auth-body">
                {{ $slot }}
            </div>
        </div>
        
        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>
