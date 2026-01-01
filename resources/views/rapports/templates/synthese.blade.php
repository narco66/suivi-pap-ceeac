@extends('rapports.templates.layout')

@section('content')
    <h1>{{ $rapport->titre ?? 'Rapport de Synthèse Institutionnel' }}</h1>
    
    <div class="info-box">
        <strong>Période :</strong> {{ $period_start->format('d/m/Y') }} - {{ $period_end->format('d/m/Y') }}<br>
        <strong>Généré le :</strong> {{ $generated_at->format('d/m/Y à H:i') }}<br>
        <strong>Généré par :</strong> {{ $generated_by->name ?? 'Système' }}<br>
        <strong>Périmètre :</strong> {{ $scope_level === 'GLOBAL' ? 'Institutionnel (Global)' : ($scope_level === 'SG' ? 'Secrétaire Général (Directions d\'Appui)' : 'Commissaire (Département Technique)') }}
    </div>
    
    <h2>Statistiques Globales</h2>
    
    @php
        $stats = $stats ?? [];
    @endphp
    
    @if(isset($stats) && is_array($stats))
    <div class="stats-container">
        <div class="stat-box">
            <span class="stat-value">{{ $stats['objectifs_total'] ?? 0 }}</span>
            <span class="stat-label">Objectifs</span>
        </div>
        <div class="stat-box">
            <span class="stat-value">{{ $stats['objectifs_en_cours'] ?? 0 }}</span>
            <span class="stat-label">En cours</span>
        </div>
        <div class="stat-box">
            <span class="stat-value">{{ $stats['actions_total'] ?? 0 }}</span>
            <span class="stat-label">Actions prioritaires</span>
        </div>
        <div class="stat-box">
            <span class="stat-value">{{ $stats['actions_en_cours'] ?? 0 }}</span>
            <span class="stat-label">Actions en cours</span>
        </div>
        <div class="stat-box">
            <span class="stat-value">{{ $stats['taches_total'] ?? 0 }}</span>
            <span class="stat-label">Tâches</span>
        </div>
        <div class="stat-box">
            <span class="stat-value">{{ $stats['taches_terminees'] ?? 0 }}</span>
            <span class="stat-label">Tâches terminées</span>
        </div>
    </div>
    
    <div class="stats-container">
        <div class="stat-box">
            <span class="stat-value">{{ $stats['taches_en_retard'] ?? 0 }}</span>
            <span class="stat-label">Tâches en retard</span>
        </div>
        <div class="stat-box">
            <span class="stat-value">{{ $stats['kpis_total'] ?? 0 }}</span>
            <span class="stat-label">KPI</span>
        </div>
        <div class="stat-box">
            <span class="stat-value">{{ $stats['kpis_sous_seuil'] ?? 0 }}</span>
            <span class="stat-label">KPI sous seuil</span>
        </div>
        <div class="stat-box">
            <span class="stat-value">{{ $stats['alertes_total'] ?? 0 }}</span>
            <span class="stat-label">Alertes</span>
        </div>
        <div class="stat-box">
            <span class="stat-value">{{ $stats['alertes_ouvertes'] ?? 0 }}</span>
            <span class="stat-label">Alertes ouvertes</span>
        </div>
        <div class="stat-box" style="background-color: #f9fafb; border-color: #ddd;">
            <span class="stat-value" style="color: #666;">-</span>
            <span class="stat-label" style="color: #999;">-</span>
        </div>
    </div>
    @else
    <div class="info-box" style="background-color: #fef3c7; border-left-color: #f59e0b;">
        <strong>Aucune statistique disponible pour cette période.</strong>
    </div>
    @endif
    
    @if(isset($objectifs) && $objectifs->count() > 0)
        <h2>Objectifs</h2>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Libellé</th>
                    <th>Statut</th>
                    <th>PAPA</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($objectifs as $objectif)
                    <tr>
                        <td>{{ $objectif->code }}</td>
                        <td>{{ $objectif->libelle }}</td>
                        <td>
                            <span class="badge badge-{{ $objectif->statut === 'termine' ? 'success' : ($objectif->statut === 'en_cours' ? 'info' : 'warning') }}">
                                {{ ucfirst($objectif->statut) }}
                            </span>
                        </td>
                        <td>{{ $objectif->papaVersion->papa->libelle ?? 'N/A' }}</td>
                        <td class="text-center">{{ $objectif->actionsPrioritaires->count() ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    @if(isset($actions) && $actions->count() > 0)
        <h2>Actions Prioritaires</h2>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Libellé</th>
                    <th>Statut</th>
                    <th>Priorité</th>
                    <th>Avancement</th>
                </tr>
            </thead>
            <tbody>
                @foreach($actions as $action)
                    <tr>
                        <td>{{ $action->code }}</td>
                        <td>{{ $action->libelle }}</td>
                        <td>
                            <span class="badge badge-{{ $action->statut === 'termine' ? 'success' : ($action->statut === 'en_cours' ? 'info' : 'warning') }}">
                                {{ ucfirst($action->statut) }}
                            </span>
                        </td>
                        <td>{{ ucfirst($action->priorite ?? 'N/A') }}</td>
                        <td class="text-center">{{ $action->pourcentage_avancement ?? 0 }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    @if(isset($kpis) && $kpis->count() > 0)
        <h2>Indicateurs de Performance (KPI)</h2>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Libellé</th>
                    <th>Valeur cible</th>
                    <th>Valeur réalisée</th>
                    <th>Réalisation</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kpis as $kpi)
                    <tr>
                        <td>{{ $kpi->code }}</td>
                        <td>{{ $kpi->libelle }}</td>
                        <td class="text-right">{{ number_format($kpi->valeur_cible ?? 0, 2) }}</td>
                        <td class="text-right">{{ number_format($kpi->valeur_realisee ?? 0, 2) }}</td>
                        <td class="text-center">{{ $kpi->pourcentage_realisation ?? 0 }}%</td>
                        <td>
                            <span class="badge badge-{{ ($kpi->pourcentage_realisation ?? 0) >= 80 ? 'success' : (($kpi->pourcentage_realisation ?? 0) >= 50 ? 'warning' : 'danger') }}">
                                {{ ucfirst($kpi->statut ?? 'N/A') }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    @if(isset($alertes) && $alertes->count() > 0)
        <h2>Alertes</h2>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Libellé</th>
                    <th>Criticité</th>
                    <th>Statut</th>
                    <th>Date création</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alertes as $alerte)
                    <tr>
                        <td>{{ $alerte->code }}</td>
                        <td>{{ $alerte->libelle }}</td>
                        <td>
                            <span class="badge badge-{{ $alerte->criticite === 'critique' ? 'danger' : ($alerte->criticite === 'vigilance' ? 'warning' : 'info') }}">
                                {{ ucfirst($alerte->criticite ?? 'N/A') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $alerte->statut === 'resolue' ? 'success' : 'warning' }}">
                                {{ ucfirst($alerte->statut ?? 'N/A') }}
                            </span>
                        </td>
                        <td>{{ $alerte->date_creation ? $alerte->date_creation->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection

