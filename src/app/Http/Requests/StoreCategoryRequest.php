<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:120',
                // Unique por usuario: mismo nombre no puede
                // repetirse para el mismo user_id.
                Rule::unique('categories')->where(
                    fn($query) => $query->where('user_id', auth()->id())
                ),
            ],
            'display_name' => ['nullable', 'string', 'max:120'],
            'description'  => ['nullable', 'string', 'max:500'],
            'type'         => ['required', 'in:income,expense'],
            'parent_id'    => [
                'nullable',
                // Si se envía parent_id debe existir y
                // pertenecer al mismo usuario.
                Rule::exists('categories', 'id')->where(
                    fn($query) => $query->where('user_id', auth()->id())
                ),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.max'      => 'El nombre no puede tener más de 120 caracteres.',
            'name.unique'   => 'Ya tienes una categoría con ese nombre.',
            'type.required' => 'El tipo es obligatorio.',
            'type.in'       => 'El tipo debe ser ingreso o gasto.',
            'parent_id.exists' => 'La categoría padre seleccionada no existe.',
        ];
    }
}
