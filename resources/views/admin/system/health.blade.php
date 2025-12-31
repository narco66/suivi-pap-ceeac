<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0 fw-bold text-ceeac-blue">
            <i class="bi bi-heart-pulse me-2"></i>Santé Système
        </h2>
    </x-slot>

    <div class="row g-4">
        <!-- Application -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-ceeac-blue text-white">
                    <h5 class="mb-0"><i class="bi bi-app me-2"></i>Application</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Nom:</strong></td>
                            <td>{{ $health['app']['name'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Environnement:</strong></td>
                            <td><span class="badge bg-info">{{ $health['app']['env'] }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Debug:</strong></td>
                            <td>
                                @if($health['app']['debug'])
                                    <span class="badge bg-warning">Activé</span>
                                @else
                                    <span class="badge bg-success">Désactivé</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Version:</strong></td>
                            <td>{{ $health['app']['version'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Base de données -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-{{ $health['database']['status'] === 'ok' ? 'success' : 'danger' }} text-white">
                    <h5 class="mb-0"><i class="bi bi-database me-2"></i>Base de données</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Statut:</strong> 
                        @if($health['database']['status'] === 'ok')
                            <span class="badge bg-success">{{ $health['database']['message'] }}</span>
                        @else
                            <span class="badge bg-danger">{{ $health['database']['message'] }}</span>
                        @endif
                    </p>
                    <p><strong>Driver:</strong> {{ $health['database']['driver'] }}</p>
                </div>
            </div>
        </div>

        <!-- Cache -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-{{ $health['cache']['status'] === 'ok' ? 'success' : 'warning' }} text-white">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Cache</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Statut:</strong> 
                        <span class="badge bg-{{ $health['cache']['status'] === 'ok' ? 'success' : 'warning' }}">
                            {{ $health['cache']['message'] }}
                        </span>
                    </p>
                    <p><strong>Driver:</strong> {{ $health['cache']['driver'] }}</p>
                </div>
            </div>
        </div>

        <!-- Stockage -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-{{ $health['storage']['status'] === 'ok' ? 'success' : ($health['storage']['status'] === 'warning' ? 'warning' : 'danger') }} text-white">
                    <h5 class="mb-0"><i class="bi bi-hdd me-2"></i>Stockage</h5>
                </div>
                <div class="card-body">
                    @if(isset($health['storage']['total']))
                        <p><strong>Total:</strong> {{ $health['storage']['total'] }}</p>
                        <p><strong>Utilisé:</strong> {{ $health['storage']['used'] }}</p>
                        <p><strong>Libre:</strong> {{ $health['storage']['free'] }}</p>
                        <p><strong>Statut:</strong> <span class="badge bg-{{ $health['storage']['status'] === 'ok' ? 'success' : ($health['storage']['status'] === 'warning' ? 'warning' : 'danger') }}">{{ $health['storage']['message'] }}</span></p>
                    @else
                        <p class="text-danger">{{ $health['storage']['message'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mail -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-envelope me-2"></i>Mail</h5>
                </div>
                <div class="card-body">
                    <p><strong>Driver:</strong> {{ $health['mail']['driver'] }}</p>
                    <p><strong>Host:</strong> {{ $health['mail']['host'] }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


