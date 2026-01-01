<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-pencil me-2"></i>Modifier la structure : {{ $structure->name }}
            </h2>
            <a href="{{ route('admin.structures.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.structures.update', $structure) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $structure->code) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $structure->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="direction" {{ old('type', $structure->type) === 'direction' ? 'selected' : '' }}>Direction</option>
                            <option value="service" {{ old('type', $structure->type) === 'service' ? 'selected' : '' }}>Service</option>
                            <option value="departement" {{ old('type', $structure->type) === 'departement' ? 'selected' : '' }}>Département</option>
                            <option value="bureau" {{ old('type', $structure->type) === 'bureau' ? 'selected' : '' }}>Bureau</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="parent_id" class="form-label">Structure parente</label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                            <option value="">Aucune (structure principale)</option>
                            @foreach($parentStructures as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $structure->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $structure->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $structure->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $structure->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="order" class="form-label">Ordre</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" 
                               id="order" name="order" value="{{ old('order', $structure->order) }}" min="0">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $structure->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Structure active
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-ceeac-primary">
                            <i class="bi bi-save me-2"></i>Enregistrer
                        </button>
                        <a href="{{ route('admin.structures.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>



