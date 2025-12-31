<?php

namespace App\Http\Requests\Papa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreObjectifRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Objectif::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'papa_version_id' => ['required', 'exists:papa_versions,id'],
            'code' => ['required', 'string', 'max:32', 'unique:objectifs,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'statut' => ['required', 'string', Rule::in(['brouillon', 'planifie', 'en_cours', 'termine', 'annule'])],
            'priorite' => ['required', 'string', Rule::in(['basse', 'normale', 'haute', 'critique'])],
            'date_debut_prevue' => ['nullable', 'date'],
            'date_fin_prevue' => ['nullable', 'date', 'after_or_equal:date_debut_prevue'],
            'date_debut_reelle' => ['nullable', 'date'],
            'date_fin_reelle' => ['nullable', 'date', 'after_or_equal:date_debut_reelle'],
            'pourcentage_avancement' => ['nullable', 'integer', 'min:0', 'max:100'],
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
            'papa_version_id.required' => 'La version du PAPA est obligatoire.',
            'papa_version_id.exists' => 'La version sélectionnée n\'existe pas.',
            'code.required' => 'Le code est obligatoire.',
            'code.unique' => 'Ce code existe déjà.',
            'code.max' => 'Le code ne peut pas dépasser 32 caractères.',
            'libelle.required' => 'Le libellé est obligatoire.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            'priorite.required' => 'La priorité est obligatoire.',
            'priorite.in' => 'La priorité sélectionnée n\'est pas valide.',
            'date_fin_prevue.after_or_equal' => 'La date de fin prévue doit être postérieure ou égale à la date de début prévue.',
            'date_fin_reelle.after_or_equal' => 'La date de fin réelle doit être postérieure ou égale à la date de début réelle.',
            'pourcentage_avancement.min' => 'Le pourcentage d\'avancement doit être entre 0 et 100.',
            'pourcentage_avancement.max' => 'Le pourcentage d\'avancement doit être entre 0 et 100.',
        ];
    }
}
