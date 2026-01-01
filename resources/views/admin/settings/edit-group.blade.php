<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-sliders me-2"></i>Paramètres : {{ $groupLabels[$group] ?? ucfirst($group) }}
            </h2>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update-group', $group) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    @if($group === 'general')
                        <div class="col-md-6">
                            <label for="app_name" class="form-label">Nom de l'application</label>
                            <input type="text" class="form-control" id="app_name" name="settings[app.name]" 
                                   value="{{ $settings['app.name']->value ?? config('app.name') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="timezone" class="form-label">Timezone</label>
                            <input type="text" class="form-control" id="timezone" name="settings[app.timezone]" 
                                   value="{{ $settings['app.timezone']->value ?? config('app.timezone') }}">
                        </div>
                    @elseif($group === 'business')
                        <div class="col-md-6">
                            <label for="papa_year" class="form-label">Année PAPA active</label>
                            <input type="number" class="form-control" id="papa_year" name="settings[papa.active_year]" 
                                   value="{{ $settings['papa.active_year']->value ?? date('Y') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="kpi_threshold" class="form-label">Seuil KPI (%)</label>
                            <input type="number" class="form-control" id="kpi_threshold" name="settings[kpi.threshold]" 
                                   value="{{ $settings['kpi.threshold']->value ?? 80 }}" min="0" max="100">
                        </div>
                    @elseif($group === 'notifications')
                        <div class="col-md-6">
                            <label for="email_enabled" class="form-label">Notifications email</label>
                            <select class="form-select" id="email_enabled" name="settings[notifications.email_enabled]">
                                <option value="1" {{ ($settings['notifications.email_enabled']->value ?? true) ? 'selected' : '' }}>Activé</option>
                                <option value="0" {{ !($settings['notifications.email_enabled']->value ?? true) ? 'selected' : '' }}>Désactivé</option>
                            </select>
                        </div>
                    @elseif($group === 'retention')
                        <div class="col-md-6">
                            <label for="retention_days" class="form-label">Durée de rétention (jours)</label>
                            <input type="number" class="form-control" id="retention_days" name="settings[retention.days]" 
                                   value="{{ $settings['retention.days']->value ?? 365 }}" min="30">
                        </div>
                    @endif

                    <div class="col-12">
                        <button type="submit" class="btn btn-ceeac-primary">
                            <i class="bi bi-save me-2"></i>Enregistrer
                        </button>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>



