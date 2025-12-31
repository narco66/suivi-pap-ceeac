<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GanttTaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Tache::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'action_prioritaire_id' => ['required', 'exists:actions_prioritaires,id'],
            'tache_parent_id' => ['nullable', 'exists:taches,id'],
            'code' => ['required', 'string', 'max:32', 'unique:taches,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'statut' => ['required', 'string', Rule::in(['brouillon', 'planifie', 'en_cours', 'termine', 'annule'])],
            'priorite' => ['required', 'string', Rule::in(['basse', 'normale', 'haute', 'critique'])],
            'criticite' => ['nullable', 'string', Rule::in(['normal', 'vigilance', 'haute', 'critique'])],
            'date_debut_prevue' => ['required', 'date'],
            'date_fin_prevue' => ['required', 'date', 'after_or_equal:date_debut_prevue'],
            'date_debut_reelle' => ['nullable', 'date'],
            'date_fin_reelle' => ['nullable', 'date', 'after_or_equal:date_debut_reelle'],
            'pourcentage_avancement' => ['nullable', 'integer', 'min:0', 'max:100'],
            'responsable_id' => ['nullable', 'exists:users,id'],
            'est_jalon' => ['nullable', 'boolean'],
            // Champs Gantt
            'baseline_start' => ['nullable', 'date'],
            'baseline_end' => ['nullable', 'date', 'after_or_equal:baseline_start'],
            'gantt_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'gantt_sort_order' => ['nullable', 'integer'],
            'is_critical' => ['nullable', 'boolean'],
            'gantt_notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'date_fin_prevue.after_or_equal' => 'La date de fin prévue doit être postérieure ou égale à la date de début prévue.',
            'date_fin_reelle.after_or_equal' => 'La date de fin réelle doit être postérieure ou égale à la date de début réelle.',
            'baseline_end.after_or_equal' => 'La date de fin baseline doit être postérieure ou égale à la date de début baseline.',
            'gantt_color.regex' => 'La couleur doit être au format hexadécimal (#RRGGBB).',
        ];
    }
}
