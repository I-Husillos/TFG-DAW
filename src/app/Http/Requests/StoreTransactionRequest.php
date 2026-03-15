<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Tipo: solo income o expense, transfer eliminado
            // porque eliminamos accounts del proyecto.
            'type'        => ['required', 'in:income,expense'],

            // Importe: positivo, máximo 13 dígitos enteros
            // y 2 decimales (decimal 15,2 en la migración).
            'amount'      => ['required', 'numeric', 'min:0.01', 'max:9999999999999.99'],

            // Moneda: código ISO 4217 de 3 letras (EUR, USD…)
            'currency'    => ['required', 'string', 'size:3'],

            // Fecha: formato estándar, no puede ser futura
            // más de 1 día para evitar errores de zona horaria.
            'date'        => ['required', 'date', 'before_or_equal:tomorrow'],

            // Categoría opcional: si se envía debe existir
            // y pertenecer al usuario autenticado.
            'category_id' => ['nullable', 'exists:categories,id'],

            'name'        => ['nullable', 'string', 'max:150'],
            'merchant'    => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'        => 'El tipo de transacción es obligatorio.',
            'type.in'              => 'El tipo debe ser ingreso o gasto.',
            'amount.required'      => 'El importe es obligatorio.',
            'amount.numeric'       => 'El importe debe ser un número.',
            'amount.min'           => 'El importe debe ser mayor que cero.',
            'currency.required'    => 'La moneda es obligatoria.',
            'currency.size'        => 'La moneda debe ser un código de 3 letras (EUR, USD…).',
            'date.required'        => 'La fecha es obligatoria.',
            'date.date'            => 'La fecha no tiene un formato válido.',
            'date.before_or_equal' => 'La fecha no puede ser futura.',
            'category_id.exists'   => 'La categoría seleccionada no existe.',
        ];
    }
}