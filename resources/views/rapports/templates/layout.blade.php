<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $rapport->titre ?? 'Rapport Institutionnel' }}</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
            size: A4;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            border-bottom: 3px solid #1e40af;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .header-logo {
            float: left;
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }
        
        .header-title {
            color: #1e40af;
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }
        
        .header-subtitle {
            color: #666;
            font-size: 9pt;
            margin: 5px 0 0 0;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        
        .footer-page {
            float: right;
        }
        
        .content {
            margin-bottom: 60px;
        }
        
        h1 {
            color: #1e40af;
            font-size: 14pt;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 5px;
        }
        
        h2 {
            color: #1e3a8a;
            font-size: 12pt;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
        }
        
        table th {
            background-color: #1e40af;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        
        table td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }
        
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .info-box {
            background-color: #f0f9ff;
            border-left: 4px solid #1e40af;
            padding: 10px;
            margin: 15px 0;
        }
        
        .stats-container {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin: 15px 0;
        }
        
        .stat-box {
            display: table-cell;
            width: 16.66%;
            padding: 12px;
            background-color: #f0f9ff;
            border: 2px solid #1e40af;
            border-radius: 4px;
            text-align: center;
            vertical-align: middle;
        }
        
        .stat-value {
            font-size: 20pt;
            font-weight: bold;
            color: #1e40af;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 9pt;
            color: #1e3a8a;
            font-weight: 600;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #10b981;
            color: white;
        }
        
        .badge-warning {
            background-color: #f59e0b;
            color: white;
        }
        
        .badge-danger {
            background-color: #ef4444;
            color: white;
        }
        
        .badge-info {
            background-color: #3b82f6;
            color: white;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mt-20 {
            margin-top: 20px;
        }
        
        .mb-20 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <div style="overflow: hidden;">
            <div class="header-title">
                COMMUNAUTÉ ÉCONOMIQUE DES ÉTATS DE L'AFRIQUE CENTRALE
            </div>
            <div class="header-subtitle">
                CEEAC - Système de Suivi des Plans d'Action Prioritaires
            </div>
        </div>
    </div>
    
    <!-- Contenu -->
    <div class="content">
        @yield('content')
    </div>
    
    <!-- Pied de page -->
    <div class="footer">
        <div style="float: left;">
            Document confidentiel - Usage interne uniquement
        </div>
        <div class="footer-page">
            Page <span class="page-number"></span> / <span class="total-pages"></span>
        </div>
    </div>
    
    <script>
        // Script pour la pagination (sera exécuté par DomPDF)
        document.addEventListener('DOMContentLoaded', function() {
            // La pagination sera gérée par DomPDF automatiquement
        });
    </script>
</body>
</html>

