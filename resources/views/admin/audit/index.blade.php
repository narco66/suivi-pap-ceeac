<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-journal-text me-2"></i>Journal d'Audit
            </h2>
            @can('export', \App\Models\AuditLog::class)
                <a href="{{ route('admin.audit.export') }}" class="btn btn-outline-primary">
                    <i class="bi bi-download me-2"></i>Exporter
                </a>
            @endcan
        </div>
    </x-slot>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.audit.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Description, acteur...">
                </div>
                <div class="col-md-2">
                    <label for="action" class="form-label">Action</label>
                    <select class="form-select" id="action" name="action">
                        <option value="">Toutes</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="module" class="form-label">Module</label>
                    <select class="form-select" id="module" name="module">
                        <option value="">Tous</option>
                        @foreach($modules as $module)
                            <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>
                                {{ ucfirst($module) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="actor_id" class="form-label">Acteur</label>
                    <select class="form-select" id="actor_id" name="actor_id">
                        <option value="">Tous</option>
                        @foreach($actors as $actor)
                            <option value="{{ $actor->id }}" {{ request('actor_id') == $actor->id ? 'selected' : '' }}>
                                {{ $actor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des logs -->
    <div class="card">
        <div class="card-body">
            @if($logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Acteur</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $log->actor?->name ?? 'Système' }}</td>
                                    <td><span class="badge bg-primary">{{ $log->action }}</span></td>
                                    <td>{{ $log->module ?? '-' }}</td>
                                    <td>{{ Str::limit($log->description, 50) }}</td>
                                    <td>
                                        @can('view', $log)
                                            <a href="{{ route('admin.audit.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $logs->links() }}
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>Aucun log d'audit trouvé.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>


