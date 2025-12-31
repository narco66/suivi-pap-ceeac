<?php

namespace App\Http\Requests\Papa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAlerteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Alerte::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in([
                'echeance_depassee',
                'retard_critique',
                'blocage',
                'anomalie',
                'escalade',
                'kpi_non_atteint',
            ])],
            'titre' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'criticite' => ['required', 'string', Rule::in(['normal', 'vigilance', 'critique'])],
            'statut' => ['required', 'string', Rule::in(['ouverte', 'en_cours', 'resolue', 'fermee'])],
            'tache_id' => ['nullable', 'exists:taches,id'],
            'action_prioritaire_id' => ['nullable', 'exists:actions_prioritaires,id'],
            'niveau_escalade' => ['nullable', 'string', Rule::in(['direction', 'sg', 'commissaire', 'presidence'])],
            'assignee_a_id' => ['nullable', 'exists:users,id'],
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
            'type.required' => 'Le type d\'alerte est obligatoire.',
            'type.in' => 'Le type d\'alerte sélectionné n\'est pas valide.',
            'titre.required' => 'Le titre est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'message.required' => 'Le message est obligatoire.',
            'criticite.required' => 'La criticité est obligatoire.',
            'criticite.in' => 'La criticité sélectionnée n\'est pas valide.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            'tache_id.exists' => 'La tâche sélectionnée n\'existe pas.',
            'action_prioritaire_id.exists' => 'L\'action sélectionnée n\'existe pas.',
            'assignee_a_id.exists' => 'L\'utilisateur assigné n\'existe pas.',
        ];
    }
}
