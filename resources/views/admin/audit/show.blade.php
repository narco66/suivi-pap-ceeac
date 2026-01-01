<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-journal-text me-2"></i>Détails du log d'audit
            </h2>
            <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-muted small">Date</label>
                    <div><strong>{{ $auditLog->created_at->format('d/m/Y à H:i:s') }}</strong></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small">Acteur</label>
                    <div>
                        @if($auditLog->actor)
                            <a href="{{ route('admin.users.show', $auditLog->actor) }}">{{ $auditLog->actor->name }}</a>
                        @else
                            <span class="text-muted">Système</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small">Action</label>
                    <div><span class="badge bg-primary">{{ $auditLog->action }}</span></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small">Module</label>
                    <div>{{ $auditLog->module ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small">Type d'objet</label>
                    <div>{{ $auditLog->object_type ? class_basename($auditLog->object_type) : '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small">ID Objet</label>
                    <div>{{ $auditLog->object_id ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <label class="form-label text-muted small">Description</label>
                    <div>{{ $auditLog->description ?? '-' }}</div>
                </div>
                @if($auditLog->metadata)
                    <div class="col-12">
                        <label class="form-label text-muted small">Métadonnées</label>
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($auditLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                @endif
                <div class="col-md-6">
                    <label class="form-label text-muted small">Adresse IP</label>
                    <div><code>{{ $auditLog->ip_address ?? '-' }}</code></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small">User Agent</label>
                    <div><small>{{ $auditLog->user_agent ?? '-' }}</small></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



