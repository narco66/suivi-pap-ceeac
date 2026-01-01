<?php

namespace App\Http\Requests\Referentiel;

use App\Models\Commission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCommissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $commission = $this->route('commission');
        $model = $commission instanceof Commission ? $commission : Commission::find($commission);

        return $model ? $this->user()->can('update', $model) : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $commission = $this->route('commission');
        $commissionId = $commission instanceof \App\Models\Commission ? $commission->id : $commission;
        
        return [
            'code' => ['required', 'string', 'max:255', Rule::unique('commissions', 'code')->ignore($commissionId)],
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
            'code.unique' => 'Ce code existe déjà.',
            'libelle.required' => 'Le libellé est obligatoire.',
        ];
    }
}
