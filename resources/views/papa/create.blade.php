<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0 fw-bold text-ceeac-blue">
            <i class="bi bi-plus-circle me-2"></i>Créer un nouveau PAPA
        </h2>
    </x-slot>

    <div class="card card-ceeac">
        <div class="card-body">
            <form method="POST" action="{{ route('papa.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="code" class="form-label">Code</label>
                    <input type="text" class="form-control form-control-ceeac" id="code" name="code" required>
                </div>
                <div class="mb-3">
                    <label for="libelle" class="form-label">Libellé</label>
                    <input type="text" class="form-control form-control-ceeac" id="libelle" name="libelle" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-ceeac">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer
                    </button>
                    <a href="{{ route('papa.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>



