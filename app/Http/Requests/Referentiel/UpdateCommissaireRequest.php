<?php

namespace App\Http\Requests\Referentiel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCommissaireRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $commissaire = $this->route('commissaire');
        if ($commissaire instanceof \App\Models\Commissaire) {
            return $this->user()->can('update', $commissaire);
        }
        return $this->user()->can('update', \App\Models\Commissaire::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'titre' => ['nullable', 'string', 'max:10', Rule::in(['M.', 'Mme', 'Dr', 'Prof', 'S.E.'])],
            'commission_id' => ['nullable', 'exists:commissions,id'],
            'pays_origine' => ['nullable', 'string', 'max:100'],
            'date_nomination' => ['nullable', 'date'],
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
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'titre.in' => 'Le titre sélectionné n\'est pas valide.',
            'commission_id.exists' => 'La commission sélectionnée n\'existe pas.',
            'pays_origine.max' => 'Le pays d\'origine ne peut pas dépasser 100 caractères.',
            'date_nomination.date' => 'La date de nomination doit être une date valide.',
        ];
    }
}
