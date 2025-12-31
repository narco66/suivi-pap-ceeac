<?php

namespace App\Http\Requests\Referentiel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDirectionTechniqueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $directionTechniqueId = $this->route('directions_technique');
        
        // Si c'est un ID (string), charger le modèle
        if (is_string($directionTechniqueId) || is_numeric($directionTechniqueId)) {
            $directionTechnique = \App\Models\DirectionTechnique::findOrFail($directionTechniqueId);
            return $this->user()->can('update', $directionTechnique);
        }
        
        // Si c'est déjà un modèle
        if ($directionTechniqueId instanceof \App\Models\DirectionTechnique) {
            return $this->user()->can('update', $directionTechniqueId);
        }
        
        return $this->user()->can('update', \App\Models\DirectionTechnique::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $directionTechniqueId = $this->route('directions_technique');
        
        // Extraire l'ID si c'est un modèle
        if ($directionTechniqueId instanceof \App\Models\DirectionTechnique) {
            $directionTechniqueId = $directionTechniqueId->id;
        }

        return [
            'code' => ['required', 'string', 'max:32', Rule::unique('directions_techniques', 'code')->ignore($directionTechniqueId)],
            'libelle' => ['required', 'string', 'max:255'],
            'departement_id' => ['nullable', 'exists:departements,id'],
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
            'code.unique' => 'Ce code est déjà utilisé par une autre direction technique.',
            'code.max' => 'Le code ne peut pas dépasser 32 caractères.',
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.max' => 'Le libellé ne peut pas dépasser 255 caractères.',
            'departement_id.exists' => 'Le département sélectionné n\'existe pas.',
        ];
    }
}
