<?php

namespace App\Http\Requests\Referentiel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDirectionAppuiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $directionAppuiId = $this->route('directions_appui');
        
        // Si c'est un ID (string), charger le modèle
        if (is_string($directionAppuiId) || is_numeric($directionAppuiId)) {
            $directionAppui = \App\Models\DirectionAppui::findOrFail($directionAppuiId);
            return $this->user()->can('update', $directionAppui);
        }
        
        // Si c'est déjà un modèle
        if ($directionAppuiId instanceof \App\Models\DirectionAppui) {
            return $this->user()->can('update', $directionAppuiId);
        }
        
        return $this->user()->can('update', \App\Models\DirectionAppui::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $directionAppuiId = $this->route('directions_appui');
        
        // Extraire l'ID si c'est un modèle
        if ($directionAppuiId instanceof \App\Models\DirectionAppui) {
            $directionAppuiId = $directionAppuiId->id;
        }

        return [
            'code' => ['required', 'string', 'max:32', Rule::unique('directions_appui', 'code')->ignore($directionAppuiId)],
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
            'code.unique' => 'Ce code est déjà utilisé par une autre direction d\'appui.',
            'code.max' => 'Le code ne peut pas dépasser 32 caractères.',
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.max' => 'Le libellé ne peut pas dépasser 255 caractères.',
        ];
    }
}
