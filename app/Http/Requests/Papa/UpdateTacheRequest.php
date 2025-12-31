<?php

namespace App\Http\Requests\Papa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTacheRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', \App\Models\Tache::find($this->route('tache')));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tacheId = $this->route('tache');
        
        return [
            'action_prioritaire_id' => ['required', 'exists:actions_prioritaires,id'],
            'tache_parent_id' => ['nullable', 'exists:taches,id'],
            'code' => ['required', 'string', 'max:32', Rule::unique('taches')->ignore($tacheId)],
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'statut' => ['required', 'string', Rule::in(['planifie', 'en_cours', 'termine', 'en_retard', 'bloque', 'annule'])],
            'priorite' => ['nullable', 'string', Rule::in(['basse', 'normale', 'haute', 'critique'])],
            'criticite' => ['nullable', 'string', Rule::in(['normal', 'vigilance', 'critique'])],
            'date_debut_prevue' => ['nullable', 'date'],
            'date_fin_prevue' => ['nullable', 'date', 'after_or_equal:date_debut_prevue'],
            'date_debut_reelle' => ['nullable', 'date'],
            'date_fin_reelle' => ['nullable', 'date', 'after_or_equal:date_debut_reelle'],
            'pourcentage_avancement' => ['nullable', 'integer', 'min:0', 'max:100'],
            'responsable_id' => ['nullable', 'exists:users,id'],
            'bloque' => ['nullable', 'boolean'],
            'raison_blocage' => ['nullable', 'string', 'max:500', 'required_if:bloque,1'],
            'est_jalon' => ['nullable', 'boolean'],
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
            'action_prioritaire_id.required' => 'L\'action prioritaire est obligatoire.',
            'action_prioritaire_id.exists' => 'L\'action prioritaire sélectionnée n\'existe pas.',
            'code.required' => 'Le code est obligatoire.',
            'code.unique' => 'Ce code est déjà utilisé par une autre tâche.',
            'code.max' => 'Le code ne peut pas dépasser 32 caractères.',
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.max' => 'Le libellé ne peut pas dépasser 255 caractères.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            'date_fin_prevue.after_or_equal' => 'La date de fin prévue doit être postérieure ou égale à la date de début prévue.',
            'date_fin_reelle.after_or_equal' => 'La date de fin réelle doit être postérieure ou égale à la date de début réelle.',
            'pourcentage_avancement.min' => 'Le pourcentage d\'avancement ne peut pas être négatif.',
            'pourcentage_avancement.max' => 'Le pourcentage d\'avancement ne peut pas dépasser 100%.',
            'responsable_id.exists' => 'Le responsable sélectionné n\'existe pas.',
            'raison_blocage.required_if' => 'La raison du blocage est obligatoire si la tâche est bloquée.',
        ];
    }
}
