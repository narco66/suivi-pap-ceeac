<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRapportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('rapport'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rapport = $this->route('rapport');
        
        return [
            'code' => ['required', 'string', 'max:32', Rule::unique('rapports', 'code')->ignore($rapport->id)],
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:papa,objectif,action_prioritaire,tache,kpi,avancement,alerte,synthese,personnalise'],
            'format' => ['required', 'string', 'in:pdf,excel,csv,html'],
            'periode' => ['required', 'string', 'in:jour,semaine,mois,trimestre,semestre,annee,personnalise'],
            'date_debut' => ['nullable', 'date', 'required_if:periode,personnalise'],
            'date_fin' => ['nullable', 'date', 'required_if:periode,personnalise', 'after_or_equal:date_debut'],
            'filtres' => ['nullable', 'array'],
            'parametres' => ['nullable', 'array'],
            'papa_id' => ['nullable', 'exists:papas,id'],
            'objectif_id' => ['nullable', 'exists:objectifs,id'],
            'est_automatique' => ['nullable', 'boolean'],
            'frequence_cron' => ['nullable', 'string', 'in:daily,weekly,monthly', 'required_if:est_automatique,true'],
            'destinataires' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Le code est requis.',
            'code.unique' => 'Ce code est déjà utilisé.',
            'titre.required' => 'Le titre est requis.',
            'type.required' => 'Le type de rapport est requis.',
            'format.required' => 'Le format est requis.',
            'periode.required' => 'La période est requise.',
            'date_debut.required_if' => 'La date de début est requise pour une période personnalisée.',
            'date_fin.required_if' => 'La date de fin est requise pour une période personnalisée.',
            'date_fin.after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.',
            'frequence_cron.required_if' => 'La fréquence est requise pour un rapport automatique.',
        ];
    }
}
