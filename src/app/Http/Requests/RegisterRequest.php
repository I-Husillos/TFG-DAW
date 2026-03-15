<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9_\-]+$/',
            ],
            'email'    => [
                'required',
                'string',
                'email',
                'max:150',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.min'      => 'El nombre de usuario debe tener al menos 3 caracteres.',
            'username.max'      => 'El nombre de usuario no puede tener más de 50 caracteres.',
            'username.unique'   => 'Este nombre de usuario ya está en uso.',
            'username.regex'    => 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos.',
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Introduce un correo electrónico válido.',
            'email.max'         => 'El correo no puede tener más de 150 caracteres.',
            'email.unique'      => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }
}
