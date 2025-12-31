<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GanttSyncRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', \App\Models\Tache::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tasks' => ['required', 'array'],
            'tasks.*.id' => ['required', 'exists:taches,id'],
            'tasks.*.start_date' => ['sometimes', 'required', 'date'],
            'tasks.*.end_date' => ['sometimes', 'required', 'date'],
            'tasks.*.progress' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'tasks.*.gantt_sort_order' => ['nullable', 'integer'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tasks.required' => 'Les données des tâches sont requises.',
            'tasks.*.id.exists' => 'Une ou plusieurs tâches n\'existent pas.',
            'tasks.*.start_date.required' => 'La date de début est requise.',
            'tasks.*.end_date.required' => 'La date de fin est requise.',
        ];
    }
}
