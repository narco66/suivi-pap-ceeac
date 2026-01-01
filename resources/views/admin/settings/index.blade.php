<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0 fw-bold text-ceeac-blue">
            <i class="bi bi-sliders me-2"></i>Paramètres Système
        </h2>
    </x-slot>

    <div class="row g-4">
        @foreach(['general' => 'Paramètres généraux', 'business' => 'Paramètres métiers', 'notifications' => 'Notifications', 'retention' => 'Rétention'] as $group => $label)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-ceeac-blue text-white">
                        <h5 class="mb-0"><i class="bi bi-gear me-2"></i>{{ $label }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Gérer les paramètres du groupe {{ $label }}</p>
                        @can('update', \App\Models\Setting::class)
                            <a href="{{ route('admin.settings.edit-group', $group) }}" class="btn btn-primary">
                                <i class="bi bi-pencil me-2"></i>Modifier
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>



