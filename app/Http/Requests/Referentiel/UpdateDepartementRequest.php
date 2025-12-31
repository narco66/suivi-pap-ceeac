<?php

namespace App\Http\Requests\Referentiel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $departementId = $this->route('departement');
        
        // Si c'est un ID (string), charger le modèle
        if (is_string($departementId) || is_numeric($departementId)) {
            $departement = \App\Models\Departement::findOrFail($departementId);
            return $this->user()->can('update', $departement);
        }
        
        // Si c'est déjà un modèle
        if ($departementId instanceof \App\Models\Departement) {
            return $this->user()->can('update', $departementId);
        }
        
        return $this->user()->can('update', \App\Models\Departement::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $departementId = $this->route('departement');
        
        // Extraire l'ID si c'est un modèle
        if ($departementId instanceof \App\Models\Departement) {
            $departementId = $departementId->id;
        }

        return [
            'code' => ['required', 'string', 'max:32', Rule::unique('departements', 'code')->ignore($departementId)],
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'actif' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Le code est obligatoire.',
            'code.unique' => 'Ce code est déjà utilisé par un autre département.',
            'code.max' => 'Le code ne peut pas dépasser 32 caractères.',
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.max' => 'Le libellé ne peut pas dépasser 255 caractères.',
        ];
    }
}
